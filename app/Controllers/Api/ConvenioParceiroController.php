<?php

namespace App\Controllers\Api;

use Http\Request;
use Controller\Controller;
use App\Models\Api\ConvenioParceiro\DestaqueModel;
use App\Models\Api\ConvenioParceiro\ParceiroEntity;
use App\Models\Api\EmailAutomatico\ParceiroModel as BIParceiro;

final class ConvenioParceiroController extends Controller
{
    public function getDestaque(Request $request)
    {
        $Parceiro = new DestaqueModel($request);
        $dado = $Parceiro->listarDados();

        return mensagemSucesso($dado);
    }

    public function getBuscar(Request $request, string $url)
    {
        $Parceiro = new ParceiroEntity();
        $Parceiro->buscar([
            ['url', $url],
            ['status', 4]
        ]);

        return mensagemSucesso([]);
    }
}
