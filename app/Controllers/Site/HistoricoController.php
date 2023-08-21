<?php

namespace App\Controllers\Site;

use Http\Response;
use Controller\Controller;
use App\Models\Site\Comunicacao\HistoricoModel;

final class HistoricoController extends Controller
{
    public function postBuscar(): Response
    {
        $Historico = new HistoricoModel();
        return mensagemSucesso($Historico->listarDados());
    }
}
