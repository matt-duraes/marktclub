<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Modules\Email;
use Controller\Controller;
use App\Models\Api\Cupom\CupomModel;
use App\Models\Api\Cupom\CupomEntity;
use System\Interface\ControllerListarInterface;

final class CupomController extends Controller implements
    ControllerListarInterface
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
