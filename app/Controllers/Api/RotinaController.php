<?php

namespace App\Controllers\Api;

use Controller\Controller;
use App\Models\Api\Analytics\Rotina\RotinaModel as AnalyticsModel;

final class RotinaController extends Controller
{
    public function analytics()
    {
        $Analytics = new AnalyticsModel('2022-11-01');
        $Analytics->rodarRotina();
        return mensagemSucesso([]);
    }
}
