<?php

namespace App\Controllers\Site;

use Http\Response;
use Controller\Controller;

final class ComoFuncionaController extends Controller
{
    public function index(): Response
    {
        return view('como_funciona.index', [
            'menu' => 'ajuda'
        ]);
    }

    public function medico(): Response
    {
        return view('como_funciona.medico', [
            'menu' => 'ajuda'
        ]);
    }

    public function dependente(): Response
    {
        return view('como_funciona.dependente', [
            'menu' => 'ajuda'
        ]);
    }

    public function funcionario(): Response
    {
        return view('como_funciona.funcionario', [
            'menu' => 'ajuda'
        ]);
    }
}
