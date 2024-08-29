<?php

namespace App\Controllers\Site;

use Http\Response;
use Controller\Controller;

final class SairController extends Controller
{
    public function index()
    {
        sessaoDestruir();
        cookieDeletar('CLT');
        return new Response(url: LINK_BOTAO_SAIR);
    }
}
