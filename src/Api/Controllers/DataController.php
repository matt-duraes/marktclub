<?php

namespace ApiController;

use Http\Request;
use Http\Response;
use ApiModel\Data\Listar;
use Controller\Controller;
use System\Interface\ControllerListarInterface;

final class DataController extends Controller implements ControllerListarInterface
{
    public function getListar(Request $request): Response
    {
        $Data = new Listar();
        $Data->set(lista: $request->dado());
        return mensagemSucesso($Data->listarDados());
    }
}
