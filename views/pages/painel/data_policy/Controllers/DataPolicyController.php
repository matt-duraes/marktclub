<?php

namespace Painel\DataPolicy\Controllers;

use Erro\Excecao;
use Http\Request;
use Controller\Controller;
use Painel\DataPolicy\Models\DataPolicy;

final class DataPolicyController extends Controller
{
    public function index()
    {
        $dado = $this->pegarDadosPeloTipo();
        painelPermissao($dado->permissao);
        $DataPolicy = new DataPolicy;

        return view('painel.data_policy.contexto', [
            'contexto' => $DataPolicy->contexto($dado->uri),
            'appTitulo' => $dado->titulo,
            'app' => $dado->menu,
        ]);
    }

    public function lista(Request $request, string $hash)
    {
        $dado = $this->pegarDadosPeloTipo();
        painelPermissao($dado->permissao);

        $pagina = $request->chave('pagina', 1);

        $DataPolicy = new DataPolicy;
        $lista = $DataPolicy->listarDados($dado->tipo, $dado->uri, $hash, $pagina);
        $nomeTag = $DataPolicy->pegarNomeContexto($hash);

        return view('painel.data_policy.lista', [
            'appTitulo' => 'LISTAR',
            'app' => $dado->menu,
            'lista' => $lista,
            'tag' => $nomeTag,
            'pagina' => (object)[
                'anterior' => $pagina > 1 ? LINK . '/' . $dado->uri . '/lista/' . $hash . '?pagina=' . $pagina - 1 : '',
                'proximo' => LINK . '/' . $dado->uri . '/lista/' . $hash . '?pagina=' . $pagina + 1,
            ],
            'appVoltar' => [LINK . '/' . $dado->uri, $dado->titulo]
        ]);
    }

    public function proposicaoDetalhe(string $hash)
    {
        $DataPolicy = new DataPolicy;
        $proposicao = $DataPolicy->proposicao($hash);
        return $this->detalhe($hash, $proposicao, $DataPolicy);
    }
    public function executivoDetalhe(string $hash)
    {
        $DataPolicy = new DataPolicy;
        $executivo = $DataPolicy->executivo($hash);
        return $this->detalhe($hash, $executivo, $DataPolicy);
    }
    private function detalhe($hash, $r, $DataPolicy)
    {
        $dado = $this->pegarDadosPeloTipo();
        painelPermissao($dado->permissao);

        $contexto = $DataPolicy->pegarHashContexto($hash);
        $pagina = $DataPolicy->pegarPaginaContexto($hash, true);

        return view('painel.data_policy.detalhe', [
            'r' => $r,
            'appTitulo' => $r->titulo,
            'appVoltar' => [
                [LINK . '/' . $dado->uri, $dado->titulo],
                [LINK . '/' . $dado->uri . '/lista/' . $contexto . $pagina, 'LISTAR']
            ],
            'app' => $dado->menu,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | GERAL
    |--------------------------------------------------------------------------
    */
    private function pegarDadosPeloTipo()
    {

        if (str_starts_with(URI, '/proposicoes-seguidas')) {
            return (object)[
                'tipo' => 'proposicao',
                'uri' => 'proposicoes-seguidas',
                'titulo' => 'Proposições seguidas',
                'menu' => 'dado-proposicao',
                'permissao' => 'dado_proposicao_index'
            ];
        } else if (str_starts_with(URI, '/atos-do-executivo')) {
            return (object)[
                'tipo' => 'executivo',
                'uri' => 'atos-do-executivo',
                'titulo' => 'Atos do executivo',
                'menu' => 'dado-executivo',
                'permissao' => 'dado_executivo_index'
            ];
        }
        throw new Excecao(status: 404);
    }
}
