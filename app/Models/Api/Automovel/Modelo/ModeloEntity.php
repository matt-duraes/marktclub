<?php

namespace App\Models\Api\Automovel\Modelo;

use ORM\Entity;
use Modules\Pagina;
use Modules\Quantidade;
use Helpers\UploadHelper;
use App\Classes\StatusGeral\Status;
use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Models\Api\Automovel\Versao\VersaoModel;
use App\Classes\ParceiroLoja\Status as StatusParceiro;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class ModeloEntity extends Entity
{
    protected string $ormTabela = TABELA_AUTOMOVEL_MODELO;
    protected array $ormBuscar = [
        'id_parceiro_loja', 'titulo', 'imagem', 'url', 'texto', 'status'
    ];
    protected array $ormSalvar = [
        'id_parceiro_loja', 'titulo', 'imagem', 'url', 'texto', 'status'
    ];
    public UploadedFile|UploadHelper|string $imagem;
    public int $id_parceiro_loja;
    public string $link_logo;
    public string $titulo;
    public string $texto;
    public Status $status;
    public string $url;
    public string $procedimento;
    protected LojaEntity $Parceiro;
    public array $versao;

    protected function regraPosBuscar()
    {
        $this->pegarParceiro();
        $this->setarStatusModelo();
        $this->link_logo = arquivoPrivado($this->imagem);
        $this->procedimento = $this->Parceiro->procedimento;
        $this->pegarListaVersao();
    }

    private function pegarParceiro()
    {
        $this->Parceiro = new LojaEntity();
        $this->Parceiro->id($this->id_parceiro_loja);
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
            modelo: $this->id
        );
        $this->versao = $VersaoModel->listarDados()->lista ?? [];
    }
}
