<?php

namespace Painel\TabelaUsuario\Controllers;

use Http\Request;
use Http\Response;
use Controller\Controller;
use Helpers\ApiHelper;
use Painel\TabelaUsuario\Models\AnalisarModel;

final class TabelaController extends Controller
{
    public function salvar()
    {
        return view(arquivo: 'painel.tabela_usuario.index', var: [
            'appTitulo' => 'TABELA / SALVAR',
            'app'       => 'tabela-salvar',
            'tipo'      => 'salvar',
            'arquivo'   => arquivoPublico('tabela', 'layout_usuario.csv', parametro: ['download' => 'sim'])
        ]);
    }

    public function bloquear()
    {
        return view(arquivo: 'painel.tabela_usuario.index', var: [
            'appTitulo' => 'TABELA / BLOQUEAR',
            'app'       => 'tabela-bloquear',
            'tipo'      => 'bloquear',
            'arquivo'   => arquivoPublico('tabela', 'layout_bloqueio.csv', parametro: ['download' => 'sim'])
        ]);
    }

    public function postAnalisar(Request $request)
    {
        $Analisar = new AnalisarModel($request->getFiles('arquivo'));

        if ($request->tipo == 'bloquear') {
            $dado = $Analisar->analisarParaBloquear();
        } elseif ($request->tipo == 'salvar') {
            $dado = $Analisar->analisarParaSalvar();
        }

        if ($dado['status'] == 'sucesso') {
            return mensagemSucesso($dado['dado']);
        }
        return new Response(json: [
            'status' => 'erro',
            'lista'  => $dado['erro']
        ], status: 400);
    }

    public function postSalvar(Request $request)
    {
        $dado = (new ApiHelper(token: true))
            ->arquivo([
                'arquivo' => $request->getFiles('arquivo')
            ])
            ->body([
                'tipo'        => $request->tipo,
                'obrigatorio' => sessao('PAINEL.obrigatorio')['usuario_cliente'] ?? []
            ])
            ->post('/tabela-usuario')
            ->array();

        return mensagemSucesso($dado);
    }
}
