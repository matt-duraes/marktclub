<?php

namespace App\Models\Api\View\Pagina;

use ORM\Entity;

final class ViewEntity extends Entity
{
    protected string $ormTabela = TABELA_VIEW_PAGINA;
    protected array $ormSalvar = ['titulo', 'url'];
    protected array $ormBuscar = ['titulo', 'url'];
    protected string $ormValidar = '
        titulo|Titulo|obrigatorio|vazio
        url|URL|obrigatorio|vazio
    ';
    public string $titulo;
    public string $url;
}
