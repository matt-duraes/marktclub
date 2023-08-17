<?php

namespace App\Controllers\Site;

use Http\Response;
use Controller\Controller;

final class AcessoRapidoController extends Controller
{
    public function index()
    {
        sessao('TEMPLATE', 'melhor-idade');
        return view(
            arquivo: 'acesso_rapido.index',
            var: [
                'sem_home' => true
            ]
        );
    }

    public function sair()
    {
        sessao('TEMPLATE', 'site');
        return new Response(url: route('index.index'));
    }
}
