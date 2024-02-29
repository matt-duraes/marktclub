<?php

namespace App\Controllers\Api\Votacao;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\Votacao\Voto\SalvarModel;
use System\Interface\ControllerSalvarInterface;

final class VotoController extends Controller implements ControllerSalvarInterface
{
    public function postSalvar(Request $request): Response
    {
        new SalvarModel(
            votacao: $request->votacao,
            usuario: $request->usuario,
            resposta: $request->resposta
        );
        return mensagemSucesso([
            'id' => uuid()
        ], status: 201);
    }
}
