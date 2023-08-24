<?php

namespace App\Models\Api\ParceiroEasylive;

use ORM\Entity;
use Modules\Data;
use Helpers\OrmHelper;
use App\Classes\Geral\Status;
use App\Classes\ParceiroEasylive\Tipo;

final class LojaEntity extends Entity
{
    protected string $ormTabela = TABELA_PARCEIRO_EASYLIVE;
    protected array $ormSalvar = [
        'id_admin_empresa', 'titulo', 'tipo', 'data_validade', 'status', 'imagem'
    ];
    protected array $ormBuscar = [
        'id_admin_empresa', 'titulo', 'tipo', 'data_validade', 'status', 'imagem'
    ];
    public string $titulo;
    public Tipo $tipo;
    public Data $data_validade;
    public Status $status;
    public string $imagem;
    public array $empresa;
    protected array $id_admin_empresa;
    public string $link_imagem;

    protected function regraSalvar()
    {
        $this->id_admin_empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->mudarListaUuidParaId($this->empresa);
    }

    protected function regraPosBuscar()
    {
        $this->empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->mudarListaIdParaUuid($this->id_admin_empresa);
        $this->link_imagem = arquivoPrivado($this->imagem);
    }
}
