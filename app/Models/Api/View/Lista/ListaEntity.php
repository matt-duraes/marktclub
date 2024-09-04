<?php

namespace App\Models\Api\View\Lista;

use ORM\Entity;
use Modules\Botao;
use Modules\ArquivoPrivado;
use App\Classes\Geral\Status;
use App\Classes\Geral\Target;
use App\Classes\Webview\Lista\Tipo;
use App\Classes\Webview\Lista\Local;
use App\Models\Api\View\Pagina\PaginaHelper;

final class ListaEntity extends Entity
{
    protected string $ormTabela = TABELA_VIEW_LISTA;
    protected array $ormSalvar = [
        'id_view_pagina', 'tipo', 'local', 'titulo', 'texto', 'link', 'target', 'arquivo',
        'api_status', 'api_scope', 'api_uri', 'api_metodo', 'api_body', 'ordem', 'status'
    ];
    protected array $ormBuscar = [
        'id_view_pagina', 'tipo', 'local', 'titulo', 'texto', 'link', 'target', 'arquivo',
        'api_status', 'api_scope', 'api_uri', 'api_metodo', 'api_body', 'ordem', 'status'
    ];
    protected int $id_view_pagina;
    public string $pagina;
    public Tipo $tipo;
    public Local $local;
    public string $titulo;
    public string $texto;
    public string $link;
    public Target $target;
    public ArquivoPrivado $arquivo;
    public Botao $api_status;
    public string $api_scope;
    public string $api_uri;
    public string $api_metodo;
    public array $api_body;
    public Status $status;
    public int $ordem;

    protected function regraSalvar()
    {
        if ($this->pExiste('pagina') && !empty($this->pagina)) {
            $this->id_view_pagina = (new PaginaHelper())->pegarIdPeloUuid($this->pagina);
        }
    }

    protected function regraPosBuscar()
    {
        $this->pagina = (new PaginaHelper())->pegarUuidPeloId($this->id_view_pagina);
    }
}
