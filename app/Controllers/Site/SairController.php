<?php

namespace App\Controllers\Site;

use Http\Response;
use Controller\Controller;

final class SairController extends Controller
{
    public function index(): Response
    {
        sessaoDestruir();
        cookieDeletar('CLT');
        return new Response(url: LINK . '/login');
    }
}
