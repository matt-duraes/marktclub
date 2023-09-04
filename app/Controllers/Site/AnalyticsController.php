<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Site\Analytics\SalvarModel;

final class AnalyticsController extends Controller
{
    public function postPagina(Request $request)
    {
        new SalvarModel($request->uri, $request->vinculo);
        return new Response(status: 204);
    }

    public function postClick()
    {
    }
}
