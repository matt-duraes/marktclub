<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerListarInterface;
use App\Models\Api\Farmacia\FarmaciaModel;

final class FarmaciaController extends Controller implements
    ControllerListarInterface
{
    public function getListar(Request $request): Response
    {
        $Farmacia = new FarmaciaModel($request);
        $dado = $Farmacia->listarDados();

        return mensagemSucesso($dado);
    }
}
