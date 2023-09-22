<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Controller\Controller;

final class TemaController extends Controller
{
    public function index(): Response
    {
        $tema = cookieExiste('TEMA') ? cookie('TEMA') : 'light';
        return view('tema', [
            'tema' => $tema
        ]);
    }

    public function postSalvar(Request $request): Response
    {
        $lista = [
            'dark'       => 'dark',
            'light'      => 'light',
            'sistema'    => 'sistema',
            'automatico' => 'automatico',
        ];
        cookie('TEMA', $lista[$request->tema] ?? 'light');
        return new Response(status: 204);
    }
}
