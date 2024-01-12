<?php

namespace App\Models\Api\Automovel\Modelo;

use App\Classes\Geral\Publicado;
use App\Classes\Geral\Status;
use App\Classes\ParceiroLoja\Procedimento;
use App\Classes\ParceiroLoja\Status as StatusParceiro;
use App\Models\Api\Automovel\Versao\VersaoModel;
use Erro\Erro;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\Entity;

final class ModeloEntity extends Entity
{
    public int $id_parceiro_loja;
    public string $imagem;
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
    protected string $ormTabela = TABELA_AUTOMOVEL_MODELO;
    protected array $ormBuscar = [
        'id_parceiro_loja', 'titulo', 'imagem', 'url', 'texto',
        'data_inicio', 'data_final', 'status', 'data_criacao',
        'data_atualizacao'
    ];
    protected array $ormSalvar = [
        'id_parceiro_loja', 'titulo', 'imagem', 'url', 'texto',
        'data_inicio', 'data_final', 'status'
    ];
    protected string $ormValidarInsert = '
        titulo|Título|obrigatorio|vazio
        parceiro|Parceiro|obrigatorio|vazio
        status|Status|obrigatorio|vazio|valido
    ';
    protected string $ormValidarUpdate = '
        titulo|Título|vazio
        parceiro|Parceiro|vazio
        status|Status|vazio|valido
    ';
    private OrmHelper $ormParceiro;

    public function __construct()
    {
        $this->ormParceiro = new OrmHelper(TABELA_PARCEIRO_LOJA);
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    protected function regraInsert(): void
    {
        $this->erroParceiroObrigatorio(is_string($this->parceiro) && empty($this->parceiro));
    }

    /**
     * @throws Excecao
     */
    private function erroParceiroObrigatorio(bool $erro): void
    {
        if (!$erro) {
            return;
        }
        mensagemErro('Campo obrigatorio!', 'O campo parceiro é obrigatório.');
    }

    /**
     * @throws Excecao
     */
    protected function regraUpdate(): void
    {
        $this->erroParceiroObrigatorio(
            $this->propriedadeExiste('parceiro') && is_string($this->parceiro) && empty($this->parceiro)
        );
    }

    /**
     * @throws Excecao
     */
    protected function regraSalvar(): void
    {
        if (is_string($this->parceiro) && !empty($this->parceiro)) {
            $this->id_parceiro_loja = $this->ormParceiro->pegarIdPeloUuid(
                $this->parceiro,
                'Parceiro não encontrado.',
                'Não encontrado'
            );
        }
        $this->imagem = arquivoPrivadoId($this->imagem);
        $this->validarDataInicioMenorQueFinal();
    }

    /**
     * @throws Excecao
     */
    private function validarDataInicioMenorQueFinal(): void
    {
        if ($this->data_inicio->date() > $this->data_final->date()) {
            mensagemErro(
                'Data Inválida!',
                'A data de inicio não pode ser maior que a data final.'
            );
        }
    }

    /**
     * @throws Excecao
     * @throws Erro
     */
    protected function regraPosBuscar(): void
    {
        $this->buscarParceiro();
        $this->imagem = arquivoPrivado($this->imagem);
        $this->pegarListaVersao();
    }

    private function buscarParceiro(): void
    {
        $Parceiro = $this->ormParceiro->pegarUltimoRegistro(
            ['id', $this->id_parceiro_loja],
            ['uuid', 'titulo', 'procedimento', 'texto_procedimento', 'status']
        );
        $ativo = (new StatusParceiro($Parceiro['status'] ?? ''))->indice() === StatusParceiro::CONCLUIDO;
        $this->publicado = new Publicado(
            $this->data_inicio,
            $this->data_final,
            $ativo
        );
        $this->procedimento = new Procedimento($Parceiro['procedimento'] ?? '');
        $this->texto_procedimento = $Parceiro['texto_procedimento'] ?? '';
        $this->parceiro = [
            'id'     => $Parceiro['uuid'],
            'titulo' => $Parceiro['titulo']
        ];
    }

    /**
     * @throws Excecao
     * @throws Erro
     */
    private function pegarListaVersao(): void
    {
        $VersaoModel = new VersaoModel(
            new Pagina(1),
            new Quantidade(50),
            modelo: $this->prop('id')
        );
        $this->versao = $VersaoModel->listarDados()->lista ?? [];
    }
}
