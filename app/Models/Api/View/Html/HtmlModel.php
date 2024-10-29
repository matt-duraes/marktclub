<?php

namespace App\Models\Api\View\Html;

use ORM\ORM;
use Modules\Botao;
use App\Classes\Geral\Metodo;
use App\Classes\Geral\Target;
use App\Classes\View\Lista\Tipo;
use App\Classes\View\Lista\Local;
use App\Classes\View\Lista\BotaoTipo;
use App\Classes\View\Lista\IconeTipo;
use App\Classes\View\Lista\ListaTipo;
use App\Classes\View\Lista\DivDirecao;
use App\Classes\View\Lista\DivPosicao;
use App\Models\Api\View\Pagina\HelperModel;

final class HtmlModel extends ORM
{
    protected string $ormTabela = TABELA_VIEW_HTML;
    private int $idPagina;
    private array $dado = [];
    public array $retorno = [];

    public function __construct(
        private string $pagina
    ) {
        parent::__construct();
        $this->pegarIdPagina();
        $this->buscarLista();
        $this->montarRetorno();
    }

    private function pegarIdPagina()
    {
        $Orm = new HelperModel();
        $id = $Orm->pegarIdPeloUuid($this->pagina);
        if (empty($id)) {
            mensagemErro('Erro!', 'Não foi encontrado página pelo ID informado.');
        }
        $this->idPagina = $id;
    }

    private function buscarLista()
    {
        $dado = $this
            ->where([
                ['id_view_pagina', $this->idPagina]
            ])
            ->order('ordem', 'ASC')
            ->read();

        if (!array_key_exists(0, $dado)) {
            return;
        }
        $this->dado = $dado;
    }

    private function montarRetorno()
    {
        $pai = [];
        $grupo = [];
        foreach ($this->dado as $r) {
            if (empty($r->id_view_html)) {
                $pai[] = $r;
                continue;
            }
            $grupo[$r->id_view_html][] = $r;
        }

        $retorno = [];
        foreach ($pai as $r) {
            $temp = $this->montarArrayRetorno($r);
            $temp['lista'] = $this->montarLista($r, $grupo);
            $retorno[] = $temp;
        }
        $this->retorno = $retorno;
    }

    private function montarLista($item, $grupo)
    {
        if (!array_key_exists($item->id, $grupo)) {
            return [];
        }
        $retorno = [];
        foreach ($grupo[$item->id] as $r) {
            $temp = $this->montarArrayRetorno($r);
            $temp['lista'] = $this->montarLista($r, $grupo);
            $retorno[] = $temp;
        }
        return $retorno;
    }

    private function montarArrayRetorno($r)
    {
        return [
            'id'              => $r->uuid,
            'tipo'            => (new Tipo($r->tipo))->indice(),
            'local'           => (new Local($r->local))->indice(),
            'titulo_interno'  => $r->titulo_interno,
            'titulo'          => $r->titulo,
            'texto'           => $r->texto,
            'link'            => $r->link,
            'target'          => (new Target($r->target))->indice(),
            'tabela'          => $r->tabela,
            'editor'          => $r->editor,
            'margem_topo'     => $r->margem_topo,
            'margem_esquerda' => $r->margem_esquerda,
            'margem_direita'  => $r->margem_direita,
            'margem_baixo'    => $r->margem_baixo,
            'imagem_arquivo'  => $r->imagem_arquivo,
            'imagem_altura'   => $r->imagem_altura,
            'icone_tipo'      => (new IconeTipo($r->icone_tipo))->indice(),
            'icone_tamanho'   => $r->icone_tamanho,
            'icone_altura'    => $r->icone_altura,
            'icone_nome'      => $r->icone_nome,
            'lista_tipo'      => (new ListaTipo($r->lista_tipo))->indice(),
            'lista_valor'     => $r->lista_valor,
            'link_empresa'    => $r->link_empresa,
            'div_direcao'     => (new DivDirecao($r->div_direcao))->indice(),
            'div_posicao'     => (new DivPosicao($r->div_posicao))->indice(),
            'api_status'      => (new Botao($r->api_status))->valor(),
            'api_metodo'      => (new Metodo($r->api_metodo))->indice(),
            'api_body'        => $r->api_body,
            'api_uri'         => $r->api_uri,
            'botao_tipo'      => (new BotaoTipo($r->botao_tipo))->indice(),
            'ordem'           => $r->ordem,
            'status'          => (new Botao($r->status ? 'sim' : 'nao'))->valor(),
        ];
    }
}
