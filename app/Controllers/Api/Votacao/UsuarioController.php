<?php

namespace App\Controllers\Api\Votacao;

use App\Models\Api\Votacao\Usuario\ValidarModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;

final class UsuarioController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postValidar(Request $request): Response
    {
        $Validar = new ValidarModel($request->usuario, $request->votacao);
        return mensagemSucesso([
            'votou' => $Validar->votou->valor()
        ]);
    }
}
