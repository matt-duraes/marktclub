<?php

namespace App\Controllers\Api\Votacao;

use Http\Request;
use Controller\Controller;
use App\Models\Api\Votacao\Usuario\ValidarModel;

final class UsuarioController extends Controller
{
    public function postValidar(Request $request)
    {
        $Validar = new ValidarModel($request->usuario, $request->votacao);
        return mensagemSucesso([
            'votou' => $Validar->votou->valor()
        ]);
    }
}
