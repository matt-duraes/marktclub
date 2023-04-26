<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerListarInterface;
use App\Models\Api\SolicitacaoPremium\PremiumModel;

final class SolicitacaoPremiumController extends Controller implements ControllerListarInterface
{
    public function getListar(Request $request): Response
    {
        $Premium = new PremiumModel($request);
        return mensagemSucesso($Premium->listarDados());
    }
}
