<?php

namespace App\Controllers\Site\Componente;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Site\Pagina\ApiModel;

final class ComponenteController extends Controller
{
    public function postBuscar(Request $request)
    {
        $Api = new ApiModel(
            url: $request->url,
            id: $request->id
        );
        return new Response(json: $Api->retorno);
    }
}
