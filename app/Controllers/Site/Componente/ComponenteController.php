<?php

namespace App\Controllers\Site\Componente;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Site\Pagina\ApiModel;
use App\Models\Site\Pagina\BuscarModel;

final class ComponenteController extends Controller
{
    public function index()
    {
        $Pagina = new BuscarModel('teste');
        return view('pagina', [
            'menu'    => 'teste',
            'html'    => $Pagina->html,
            'url'     => 'teste'
        ]);
    }

    public function postBuscar(Request $request)
    {
        $Api = new ApiModel(
            url: $request->url,
            id: $request->id,
            campo: $request->campo
        );
        return new Response(json: $Api->retorno, status: 201);
    }
}
