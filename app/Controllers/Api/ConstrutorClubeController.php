<?php

namespace App\Controllers\Api;

use Controller\Controller;
use App\Models\Api\AdminConstrutor\ClubeModel;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;

final class ConstrutorClubeController extends Controller
{
    public function getClube(string $url)
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->buscar([
            ['link_site', $url],
            ['status', 1]
        ]);
        $Clube = new ClubeModel($Construtor);
        return mensagemSucesso($Clube->construtor);
    }
}
