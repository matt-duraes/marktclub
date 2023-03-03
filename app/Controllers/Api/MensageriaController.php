<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\Mensageria\MensageriaEntity;
use System\Interface\ControllerSalvarInterface;

final class MensageriaController extends Controller implements
    ControllerSalvarInterface
{
    public function postSalvar(Request $request): Response
    {
        $Mensageria = new MensageriaEntity(
            tipo: $request->tipo,
            payload: base64Decode($request->payload)
        );
        $Mensageria->salvar();

        return mensagemSucesso(
            pegarPropriedadeDaEntity($Mensageria, lista: ['id', 'tipo', 'status']),
            status: 201
        );
    }
}
