<?php

namespace App\Controllers\Api;

use Http\Request;
use Controller\Controller;
use App\Models\Api\ConvenioParceiro\ParceiroDestaqueModel;

final class ConvenioParceiroController extends Controller
{
    public function getDestaque(Request $request)
    {
        $Parceiro = new ParceiroDestaqueModel($request);
        $dado = $Parceiro->listarDados();

        return mensagemSucesso($dado);
    }
}
