<?php

namespace Painel\UsuarioTabela\Controllers;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Controller\Controller;
use Painel\UsuarioTabela\Models\AnalisarModel;

final class TabelaController extends Controller
{
    public function salvar()
    {
        return view(arquivo: 'painel.usuario_tabela.index', var: [
            'appTitulo' => 'TABELA / SALVAR',
            'app' => 'tabela-salvar',
            'tipo' => 'salvar',
            'arquivo' => arquivoPublico('tabela', 'layout_usuario.csv', parametro: ['download' => 'sim'])
        ]);
    }

    public function bloquear()
    {
        return view(arquivo: 'painel.usuario_tabela.index', var: [
            'appTitulo' => 'TABELA / BLOQUEAR',
            'app' => 'tabela-bloquear',
            'tipo' => 'bloquear',
            'arquivo' => arquivoPublico('tabela', 'layout_bloqueio.csv', parametro: ['download' => 'sim'])
        ]);
    }

    public function postAnalisarSalvar(Request $request)
    {
        $Analisar = new AnalisarModel($request->getFiles('arquivo'));
        $dado = $Analisar->analisarParaSalvar();

        if ($dado['status'] == 'sucesso') {
            return mensagemSucesso($dado['dado']);
        }
        return new Response(json: [
            'status' => 'erro',
            'lista' => $dado['erro']
        ], status: 400);
    }

    public function postAnalisarBloquear(Request $request)
    {
        $Analisar = new AnalisarModel($request->getFiles('arquivo'));
        $dado = $Analisar->analisarParaBloquear();

        if ($dado['status'] == 'sucesso') {
            return mensagemSucesso($dado['dado']);
        }
        return new Response(json: [
            'status' => 'erro',
            'lista' => $dado['erro']
        ], status: 400);
    }

    public function postSalvar(Request $request)
    {
        $hash = $request->hash;
        if (!is_array($hash) || !$hash) {
            mensagemErro('Erro!', 'Não foi possível analisar dados para salvar usuário, por favor, tente novamente.');
        }

        $Api = new ApiHelper(token: true);
        $resposta = $Api->body([
            'hash' => $hash
        ])->post('/tabela/salvar')->object();

        return new Response(json: $resposta, status: 201);
    }

    public function postBloquear(Request $request)
    {
        $Api = new ApiHelper(token: true);
        $resposta = $Api->body(['hash' => $request->hash])->post('/tabela/bloquear')->object();

        return new Response(json: $resposta, status: 201);
    }
}
