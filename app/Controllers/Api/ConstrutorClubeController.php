<?php

namespace App\Controllers\Api;

use Controller\Controller;
use App\Models\Api\ConstrutorClube\ClubeModel;
use App\Models\Api\ConstrutorClube\ConstrutorEntity;

final class ConstrutorClubeController extends Controller
{
    public function getClube(string $url)
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->buscar([
            ['link_clube', strDominio($url)],
            ['status', 1]
        ]);
        $Clube = new ClubeModel($Construtor);
        return mensagemSucesso($Clube->construtor);
    }
}
