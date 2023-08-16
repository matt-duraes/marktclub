<?php

namespace App\Models\Api\Automovel\Modelo;

use ORM\Entity;
use Modules\Pagina;
use Modules\Quantidade;
use App\Classes\Geral\Status;
use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Models\Api\Automovel\Versao\VersaoModel;
use App\Classes\ParceiroLoja\Status as StatusParceiro;

final class ModeloEntity extends Entity
{
    protected string $ormTabela = TABELA_AUTOMOVEL_MODELO;
    protected array $ormBuscar = [
        'id_parceiro_loja', 'titulo', 'imagem', 'url', 'texto', 'status'
    ];
    protected array $ormSalvar = [
        'id_parceiro_loja', 'titulo', 'imagem', 'url', 'texto', 'status'
    ];
    public string $imagem;
    public int $id_parceiro_loja;
    public string $link_imagem;
    public string $titulo;
    public string $texto;
    public Status $status;
    public string $url;
    public string $procedimento;
    public string $texto_procedimento;
    protected LojaEntity $Parceiro;
    public array $versao;
    public string $parceiro;

    public function regraSalvar()
    {
        if ($this->propriedadeExiste('parceiro') && !empty($this->parceiro)) {
            $this->pegarParceiro($this->parceiro);
            $this->id_parceiro_loja = $this->Parceiro->get('id');
        }
    }

    protected function regraPosBuscar()
    {
        $this->pegarParceiro($this->id_parceiro_loja);
        $this->setarStatusModelo();
        $this->link_imagem = arquivoPrivado($this->imagem);
        $this->procedimento = $this->Parceiro->procedimento;
        $this->texto_procedimento = $this->Parceiro->texto_procedimento;
        $this->pegarListaVersao();
    }

    private function pegarParceiro($id)
    {
        $this->Parceiro = new LojaEntity();
        if (is_int($id)) {
            $this->Parceiro->id($id);
            return;
        }
        $this->Parceiro->uuid($id, mensagem: 'Parceiro não encontrado.');
    }

    private function setarStatusModelo()
    {
        if ($this->Parceiro->status->indice() == StatusParceiro::CONCLUIDO) {
            return;
        }
        $this->status = new Status(Status::INATIVO);
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
