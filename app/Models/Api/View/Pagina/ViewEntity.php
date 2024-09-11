<?php

namespace App\Models\Api\View\Pagina;

use ORM\Entity;

final class ViewEntity extends Entity
{
    protected string $ormTabela = TABELA_VIEW_PAGINA;
    protected array $ormSalvar = ['titulo', 'url', 'html'];
    protected array $ormBuscar = ['titulo', 'url', 'html'];
    protected string $ormValidar = '
        titulo|Titulo|obrigatorio|vazio
        url|URL|obrigatorio|vazio
        html|HTML|obrigatorio|vazio
    ';

    public string $titulo;
    public string $url;
    public array $html;
}
