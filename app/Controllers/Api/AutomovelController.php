<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerListarInterface;
use App\Models\Api\Automovel\Automovel\AutomovelModel;

final class AutomovelController extends Controller implements
    ControllerListarInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Automovel = new AutomovelModel($request);

        $dado = $Automovel->listarDados();

        return mensagemSucesso($dado);
    }
}
