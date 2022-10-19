<?php

namespace App\Controllers\Api;

use Http\Request;
use Controller\Controller;
use App\Controllers\Api\Interface\SalvarInterface;
use App\Models\Api\UsuarioCliente\UsuarioTabelaModel;

final class TabelaController extends Controller implements
    SalvarInterface
{
    public function postSalvar(Request $request)
    {
        $Usuario = new UsuarioTabelaModel();
        $hash = jsonDecode($request->hash, true, true);
        foreach ($hash as $item) {
            $Usuario->salvarUsuario($item);
        }
        $retorno = $Usuario->retorno();

        return mensagemSucesso([
            'retorno' => $retorno,
            'tipo' => 'salvar'
        ], status: 201);
    }

    public function postBloquear(Request $request)
    {
        $Usuario = new UsuarioTabelaModel();
        $hash = jsonDecode($request->hash, true, true);

        foreach ($hash as $item) {
            $Usuario->bloquearUsuario($item);
        }
        $retorno = $Usuario->retorno();

        return mensagemSucesso([
            'retorno' => $retorno,
            'tipo' => 'bloquear'
        ], status: 201);
    }
}
