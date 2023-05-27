<?php

namespace App\Controllers\Api;

use Http\Response;
use Controller\Controller;
use App\Helpers\FenaeLoginHelper;

final class FenaeLoginController extends Controller
{
    public function paginaLogin()
    {
        $Login = new FenaeLoginHelper();
        return new Response(url: $Login->pegarLinkAutorizacao());
    }
}
