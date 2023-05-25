<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\ComercialSubempresa\SelectModel;

final class ComercialSubempresaController extends Controller
{
    public function getSelect(Request $request): Response
    {
        $Empresa = new SelectModel($request);
        $dado = $Empresa->listarSelect();

        return mensagemSucesso($dado);
    }
}
