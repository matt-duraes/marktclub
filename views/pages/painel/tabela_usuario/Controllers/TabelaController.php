<?php

namespace Painel\TabelaUsuario\Controllers;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Controller\Controller;
use Painel\TabelaUsuario\Models\AnalisarModel;

final class TabelaController extends Controller
{
    public function salvar()
    {
        return view(arquivo: 'painel.tabela_usuario.index', var: [
            'appTitulo' => 'TABELA / SALVAR',
            'app'       => 'tabela-salvar',
            'tipo'      => 'salvar',
            'arquivo'   => linkDownloadPainel(DIRETORIO_PUBLICO . '/tabela/layout_usuario.csv')
        ]);
    }

    public function bloquear()
    {
        return view(arquivo: 'painel.tabela_usuario.index', var: [
            'appTitulo' => 'TABELA / BLOQUEAR',
            'app'       => 'tabela-bloquear',
            'tipo'      => 'bloquear',
            'arquivo'   => linkDownloadPainel(DIRETORIO_PUBLICO . '/tabela/layout_bloqueio.csv')
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
        $obrigatorio = sessao('PAINEL.obrigatorio');
        if (is_object($obrigatorio) && validarIndiceExiste($obrigatorio, 'usuario_cliente')) {
            $obrigatorio = $obrigatorio->usuario_cliente;
        }
        if (empty($obrigatorio)) {
            return mensagemErro('Campo vazio!', 'Campos obrigatórios não preenchidos.');
        }

        $dado = (new ApiHelper(token: true))
            ->arquivo([
                'arquivo' => $request->getFiles('arquivo')
            ])
            ->body([
                'tipo'        => $request->tipo,
                'obrigatorio' => $obrigatorio
            ])
            ->post('/tabela-usuario')
            ->array();

        return mensagemSucesso($dado);
    }
}
