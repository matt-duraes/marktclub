<?php

namespace App\Controllers\Site;

use Http\Request;
use Controller\Controller;
use App\Models\Site\Endereco\ListaModel;
use App\Models\Site\Endereco\EstruturaModel;
use App\Models\Site\Endereco\PrincipalModel;

final class EnderecoController extends Controller
{
    public function postPrincipal(Request $request)
    {
        $Endereco = new PrincipalModel(
            id: $request->id,
            local: $request->local,
            latitude: $request->latitude,
            longitude: $request->longitude,
        );
        return mensagemSucesso($Endereco->endereco);
    }

    public function postLista(Request $request)
    {
        $Lista = new ListaModel(
            id: $request->id,
            local: $request->local,
            estado: $request->estado,
            cidade: $request->cidade,
            pagina: $request->pagina
        );
        return mensagemSucesso($Lista->endereco);
    }

    public function postEstrutura(Request $request)
    {
        $Lista = new EstruturaModel(
            id: $request->id,
            local: $request->local,
            pais: $request->pais,
            estado: $request->estado,
            cidade: $request->cidade,
        );
        return mensagemSucesso($Lista->lista);
    }
}
