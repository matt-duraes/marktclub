<?php

namespace App\Controllers\Cdn;

use Http\Request;
use Controller\Controller;
use App\Models\Api\Analytics\AnalyticsModel;

final class AnalyticsController extends Controller
{
    public function getAnalytics(Request $request)
    {
        $Relatorio = new AnalyticsModel($request);
        $dado = $Relatorio->pegarRelatorio();
        return mensagemSucesso($dado);
    }
}
