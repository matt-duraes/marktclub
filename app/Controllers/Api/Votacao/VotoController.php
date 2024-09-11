<?php

namespace App\Controllers\Api\Votacao;

use App\Models\Api\Votacao\Voto\SalvarModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerSalvarInterface;

final class VotoController extends Controller implements
    ControllerSalvarInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        new SalvarModel($request->votacao, $request->usuario, $request->resposta);
        return mensagemSucesso([
            'id' => uuid()
        ], 201);
    }
}
