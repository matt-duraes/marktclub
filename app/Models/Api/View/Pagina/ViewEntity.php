<?php

namespace App\Models\Api\View\Pagina;

use ORM\Entity;
use Helpers\OrmHelper;
use App\Classes\Geral\Status;
use App\Models\Api\View\Html\HtmlModel;

final class ViewEntity extends Entity
{
    protected string $ormTabela = TABELA_VIEW_PAGINA;
    protected array $ormSalvar = ['id_admin_empresa', 'titulo', 'url', 'status'];
    protected array $ormBuscar = ['id_admin_empresa', 'titulo', 'url', 'status'];
    protected string $ormValidar = '
        titulo|Titulo|obrigatorio|vazio
        url|URL|obrigatorio|vazio
        status|Status|obrigatorio|vazio|valido
    ';
    protected array $id_admin_empresa;
    public array $empresa;
    public string $titulo;
    public string $url;
    public Status $status;
    public array $html;

    private OrmHelper $EmpresaOrm;

    public function __construct()
    {
        parent::__construct();
        $this->EmpresaOrm = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
    }

    protected function regraSalvar()
    {
        if($this->pExiste('empresa')) {
            $this->id_admin_empresa = $this->EmpresaOrm->mudarListaUuidParaId($this->empresa);
        }
    }
    protected function regraPosBuscar()
    {
        $this->empresa = $this->EmpresaOrm->mudarListaIdParaUuid($this->id_admin_empresa);
        if (defined('TOKEN') && TOKEN['app']->audience != 'painel' && !in_array(TOKEN['empresa']->id, $this->id_admin_empresa)) {

            $this->html = (new HtmlModel($this->id))->retorno;
            return;
        }
        $this->html = (new HtmlModel($this->id))->retorno;
    }
}
