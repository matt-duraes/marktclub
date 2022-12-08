<?php

namespace App\Controllers\Doc;

use Http\Response;
use Controller\Controller;

final class IndexController extends Controller
{
    public function index()
    {
        if (!eLocalhost()) {
            return new Response(status: 404);
        }
        return new Response(url: LINK_PADRAO . '/__documentacao');
    }
}
