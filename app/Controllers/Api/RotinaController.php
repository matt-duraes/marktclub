<?php

namespace App\Controllers\Api;

use Controller\Controller;
use App\Models\Api\Analytics\Rotina\AnalyticsModel;
use App\Models\Api\UsuarioCliente\Rotina\UsuarioModel;

final class RotinaController extends Controller
{
    public function analytics()
    {
        $Analytics = new AnalyticsModel('2022-11-01');
        $Analytics->rodarRotina();
        return mensagemSucesso([]);
    }

    public function baseUsuario()
    {
        $Usuario = new UsuarioModel();
        return mensagemSucesso([]);
    }
}
