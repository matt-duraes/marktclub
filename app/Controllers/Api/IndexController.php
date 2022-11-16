<?php

namespace App\Controllers\Api;

use Http\Response;
use Controller\Controller;

final class IndexController extends Controller
{
    public function index(): Response
    {
        return new Response('');
    }
}
