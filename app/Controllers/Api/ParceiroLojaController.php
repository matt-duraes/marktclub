<?php

namespace App\Controllers\Api;

use Http\Request;
use Controller\Controller;
use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Models\Api\ParceiroLoja\DestaqueModel;

final class ParceiroLojaController extends Controller
{
    public function getDestaque(Request $request)
    {
        $Parceiro = new DestaqueModel($request);
        $dado = $Parceiro->listarDados();

        return mensagemSucesso($dado);
    }

    public function getBuscar(Request $request, string $url)
    {
        $Parceiro = new LojaEntity();
        $Parceiro->buscar([
            ['url', $url],
            ['status', 4]
        ]);

        return mensagemSucesso([]);
    }
}
