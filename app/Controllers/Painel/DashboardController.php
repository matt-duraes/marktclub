<?php

namespace App\Controllers\Painel;

use Controller\Controller;
use App\Classes\UsuarioCliente\TipoPagamento;

final class DashboardController extends Controller
{
    public function index()
    {
        return view(arquivo: 'dashboard', var: [
            'appTitulo' => 'Dashboard',
            'app' => 'dashboard'
        ]);
    }
}
