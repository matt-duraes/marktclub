<?php

namespace App\Models\Api\View\Lista;

use ORM\ORM;
use Http\Request;
use Modules\Botao;
use App\Classes\Geral\Status;
use App\Classes\Geral\Target;
use App\Classes\Webview\Lista\Tipo;
use App\Classes\Webview\Lista\Local;
use App\Models\Api\View\Pagina\PaginaHelper;

final class ListaModel extends ORM
{
    protected string $ormTabela = TABELA_VIEW_LISTA;
    public array $retorno = [];
    public string $pagina;
    private int $idPagina;

    public function __construct(
        Request $request
    ) {
        parent::__construct();
        $this->set(lista: $request->dado());
        $this->pegarIdPagina();
        $this->listarDados();
    }

    private function pegarIdPagina()
    {
        $this->idPagina = (new PaginaHelper())->pegarIdPeloUuid($this->pagina);
    }

    private function listarDados()
    {
        $lista = $this
            ->campo([
                'uuid', 'tipo', 'local', 'titulo', 'texto', 'link', 'target', 'arquivo', 'api_status', 'api_scope',
                'api_uri', 'api_metodo', 'api_body', 'status'
            ])
            ->where(['id_view_pagina', $this->idPagina])
            ->order('ordem', 'ASC')
            ->read();
        if (!is_array($lista) || !array_key_exists(0, $lista)) {
            return [];
        }
        $this->retorno = $this->montarRetorno($lista);
    }

    private function montarRetorno(array $lista)
    {
        $retorno = [];
        $Tipo = new Tipo();
        $Local = new Local();
        $Target = new Target();
        $Botao = new Botao();
        $Status = new Status();
        foreach ($lista as $r) {
            $retorno[] = [
                'id'         => $r->uuid,
                'tipo'       => $Tipo->indice($r->tipo),
                'local'      => $Local->indice($r->local),
                'titulo'     => $r->titulo,
                'texto'      => $r->texto,
                'link'       => $r->link,
                'target'     => $Target->indice($r->target),
                'arquivo'    => arquivoPrivado($r->arquivo),
                'api_status' => $Botao->valor($r->api_status),
                'api_scope'  => $r->api_scope,
                'api_uri'    => $r->api_uri,
                'api_metodo' => $r->api_metodo,
                'api_body'   => $r->api_body,
                'status'     => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }
}
