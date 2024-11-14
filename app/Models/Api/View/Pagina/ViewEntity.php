<?php

namespace App\Models\Api\View\Pagina;

use ORM\Entity;
use App\Classes\Geral\Status;
use App\Models\Api\View\Html\HtmlModel;

final class ViewEntity extends Entity
{
    protected string $ormTabela = TABELA_VIEW_PAGINA;
    protected array $ormSalvar = ['titulo', 'url', 'status'];
    protected array $ormBuscar = ['titulo', 'url', 'status'];
    protected string $ormValidar = '
        titulo|Titulo|obrigatorio|vazio
        url|URL|obrigatorio|vazio
        status|Status|obrigatorio|vazio|valido
    ';
    public string $titulo;
    public string $url;
    public Status $status;
    public array $html;

    protected function regraPosBuscar()
    {
        if (defined('TOKEN') && TOKEN['app']->audience != 'painel') {
            $this->html = (new HtmlModel($this->id))->retorno;
        }
    }
}
