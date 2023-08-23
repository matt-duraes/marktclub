<?php

namespace App\Models\Api\Automovel\Modelo;

use ORM\Entity;
use Modules\Data;
use Modules\Pagina;
use Helpers\OrmHelper;
use Modules\Quantidade;
use App\Classes\Geral\Status;
use App\Classes\Geral\Publicado;
use App\Classes\ParceiroLoja\Procedimento;
use App\Models\Api\Automovel\Versao\VersaoModel;
use App\Classes\ParceiroLoja\Status as StatusParceiro;

final class ModeloEntity extends Entity
{
    protected string $ormTabela = TABELA_AUTOMOVEL_MODELO;
    protected array $ormBuscar = [
        'id_parceiro_loja', 'titulo', 'imagem', 'url', 'texto', 'data_inicio', 'data_final', 'status'
    ];
    protected array $ormSalvar = [
        'id_parceiro_loja', 'titulo', 'imagem', 'url', 'texto', 'data_inicio', 'data_final', 'status'
    ];
    public string $imagem;
    public int $id_parceiro_loja;
    public string $titulo;
    public string $texto;
    public Status $status;
    public string $url;
    public Procedimento $procedimento;
    public string $texto_procedimento;
    public array $versao;
    public string|array $parceiro;
    public Data $data_inicio;
    public Data $data_final;
    public Publicado $publicado;
    private OrmHelper $ormParceiro;

    public function __construct()
    {
        $this->ormParceiro = new OrmHelper(TABELA_PARCEIRO_LOJA);
        parent::__construct();
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT/UPDATE
    |--------------------------------------------------------------------------
    */
    public function regraInsert()
    {
        $this->erroParceiroObrigatorio(is_string($this->parceiro) && empty($this->parceiro));
    }

    public function regraUpdate()
    {
        $this->erroParceiroObrigatorio(
            $this->propriedadeExiste('parceiro') && is_string($this->parceiro) && empty($this->parceiro)
        );
    }

    private function erroParceiroObrigatorio(bool $erro)
    {
        if (!$erro) {
            return;
        }
        mensagemErro('Campo obrigatorio!', 'O campo parceiro é obrigatório.');
    }

    public function regraSalvar()
    {
        if (is_string($this->parceiro) && !empty($this->parceiro)) {
            $this->id_parceiro_loja = $this->ormParceiro->pegarIdPeloUuid($this->parceiro);
        }
        $this->imagem = arquivoPrivadoId($this->imagem);
    }

    /*
    |--------------------------------------------------------------------------
    | READ
    |--------------------------------------------------------------------------
    */
    protected function regraPosBuscar()
    {
        $this->buscarParceiro();
        $this->imagem = arquivoPrivado($this->imagem);
        $this->pegarListaVersao();
    }

    private function buscarParceiro()
    {
        $Parceiro = $this->ormParceiro->pegarUltimoRegistro(
            where: ['id', $this->id_parceiro_loja],
            campo: ['uuid', 'titulo', 'procedimento', 'texto_procedimento', 'status']
        );
        $this->publicado = new Publicado(
            $this->data_inicio,
            $this->data_final,
            (new StatusParceiro($Parceiro['status'] ?? ''))->indice() == StatusParceiro::CONCLUIDO
        );
        $this->procedimento = new Procedimento($Parceiro['procedimento'] ?? '');
        $this->texto_procedimento = $Parceiro['texto_procedimento'] ?? '';
        $this->parceiro = [
            'id'     => $Parceiro['uuid'],
            'titulo' => $Parceiro['titulo']
        ];
    }

    private function pegarListaVersao()
    {
        $VersaoModel = new VersaoModel(
            pagina: new Pagina(1),
            quantidade: new Quantidade(50),
            modelo: $this->prop('id')
        );
        $this->versao = $VersaoModel->listarDados()->lista ?? [];
    }
}
