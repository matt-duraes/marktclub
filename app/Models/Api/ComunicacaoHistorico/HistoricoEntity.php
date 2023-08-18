<?php

namespace App\Models\Api\ComunicacaoHistorico;

use ORM\Entity;
use Modules\Data;
use Helpers\OrmHelper;
use App\Classes\Geral\Status;
use App\Classes\Geral\Publicado;

final class HistoricoEntity extends Entity
{
    protected string $ormTabela = TABELA_COMUNICACAO_HISTORICO;
    protected array $ormBuscar = [
        'titulo', 'data_inicio', 'data_final', 'id_parceiro_loja', 'id_admin_empresa',
        'data_criacao', 'status', 'imagem'
    ];
    protected array $ormSalvar = [
        'titulo', 'data_inicio', 'data_final', 'id_parceiro_loja', 'id_admin_empresa',
        'status', 'imagem'
    ];
    protected array $id_admin_empresa;
    protected int $id_parceiro_loja;
    public string $parceiro;
    public array $empresa = [];
    public Status $status;
    public string $titulo;
    public Data $data_inicio;
    public Data $data_final;
    public string $imagem;
    public string $link_imagem;
    public Publicado $publicado;

    protected function regraSalvar()
    {
        $this->id_admin_empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->mudarListaUuidParaId($this->empresa);
        $this->id_parceiro_loja = (new OrmHelper(TABELA_PARCEIRO_LOJA))->pegarIdPeloUuid($this->parceiro);
    }

    protected function regraPosBuscar()
    {
        $this->empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->mudarListaIdParaUuid($this->id_admin_empresa);
        $this->parceiro = (new OrmHelper(TABELA_PARCEIRO_LOJA))->pegarUuidPeloId($this->id_parceiro_loja);
        $this->link_imagem = arquivoPrivado($this->imagem);

        $this->publicado = new Publicado(
            $this->data_inicio,
            $this->data_final,
            $this->status->indice() == Status::ATIVO
        );
    }
}
