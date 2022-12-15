<?php

namespace App\Controllers\Api;

use Http\Response;
use Controller\Controller;
use App\Models\Api\Analytics\Rotina\AnalyticsModel;
use App\Models\Api\EmailAutomatico\UltimoAcessoModel;

final class RotinaController extends Controller
{
    public function analytics()
    {
        $Analytics = new AnalyticsModel('2022-11-01');
        $Analytics->rodarRotina();
        return mensagemSucesso([]);
    }

    public function ultimoAcesso()
    {
        new UltimoAcessoModel();
        return (new Response)->status(201);
    }
}
