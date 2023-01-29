<?php

namespace App\Controllers\Api;

use Http\Request;
use Modules\Data;
use Http\Response;
use Controller\Controller;
use App\Models\Api\Rotina\RelatorioUsuarioModel;
use App\Models\Api\Rotina\RelatorioAnalyticsModel;
use App\Models\Api\EmailAutomatico\UltimoAcessoModel;

final class RotinaController extends Controller
{
    public function getRelatorioAnalytics(Request $request)
    {
        $Rotina = new RelatorioAnalyticsModel(new Data($request->data));
        $Rotina->rodarRotina();
        return mensagemSucesso([], status: 201);
    }

    public function getRelatorioUsuario()
    {
        $Rotina = new RelatorioUsuarioModel();
        $Rotina->rodarRotina();

        return mensagemSucesso([], status: 201);
    }

    public function ultimoAcesso()
    {
        new UltimoAcessoModel();
        return (new Response)->status(201);
    }
}
