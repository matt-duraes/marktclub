<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Modules\Email;
use Controller\Controller;
use App\Classes\Cupom\Helper;
use App\Classes\Cupom\Status;
use App\Models\Api\Cupom\CupomModel;
use App\Models\Api\Cupom\CupomEntity;
use System\Interface\ControllerListarInterface;

final class CupomController extends Controller implements
    ControllerListarInterface
{
    public function getListar(Request $request): Response
    {
        $CupomHelper = new CupomModel($request);
        $listar = $CupomHelper->listarDados();

        return mensagemSucesso($listar);
    }


}
