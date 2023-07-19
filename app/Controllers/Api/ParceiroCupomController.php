<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\ParceiroCupom\CupomModel;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;

final class ParceiroCupomController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface
{
    public function getListar(Request $request): Response
    {
        $CupomHelper = new CupomModel();
        $listar = $CupomHelper->listarDados($request);

        return mensagemSucesso($listar);
    }

    public function getBuscar(string $id): Response
    {
        $CupomHelper = new CupomModel();
        $listar = $CupomHelper->buscarDados($id);

        return mensagemSucesso($listar);
    }
}
