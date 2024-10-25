<?php

namespace App\Models\Api\View\Pagina;

use ORM\Entity;
use App\Classes\Geral\Status;

final class ViewEntity extends Entity
{
    protected string $ormTabela = TABELA_VIEW_PAGINA;
    protected array $ormSalvar = ['titulo', 'url', 'html', 'status'];
    protected array $ormBuscar = ['titulo', 'url', 'html', 'status'];
    protected string $ormValidar = '
        titulo|Titulo|obrigatorio|vazio
        url|URL|obrigatorio|vazio
        html|HTML|obrigatorio|vazio
        status|Status|obrigatorio|vazio|valido
    ';
    public string $titulo;
    public string $url;
    public array $html;
    public Status $status;
}
