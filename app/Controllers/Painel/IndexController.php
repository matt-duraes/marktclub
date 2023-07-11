<?php

namespace App\Controllers\Painel;

use Http\Response;
use Controller\Controller;

final class IndexController extends Controller
{
    public function index()
    {
        return new Response(url: LINK . '/dashboard');
    }
}
