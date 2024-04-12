<?php

namespace App\Controllers\Site;

use Http\Request;
use Controller\Controller;
use App\Models\Site\Endereco\ListaModel;

final class EnderecoController extends Controller
{
    public function postIndex(Request $request)
    {
        $Lista = new ListaModel(
            id: $request->id,
            local: $request->local
        );
        return mensagemSucesso([
            'existe'     => $Lista->existe,
            'quantidade' => $Lista->quantidade,
            'cidade'     => $Lista->cidade,
            'estado'     => $Lista->estado,
            'pais'       => $Lista->pais,
            'principal'  => $Lista->principal,
            'endereco'   => $Lista->endereco
        ]);
    }
}
