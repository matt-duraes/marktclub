<?php

namespace App\Controllers\Site;

use Http\Request;
use Controller\Controller;
use System\Classes\Endereco\Tipo;
use System\Classes\Endereco\Local;
use App\Models\Site\Endereco\ListaModel;

final class EnderecoController extends Controller
{
    public function postIndex(Request $request)
    {
        $Lista = new ListaModel(
            id: $request->id,
            tipo: new Tipo($request->tipo),
            local: new Local(Local::CLUBE)
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
