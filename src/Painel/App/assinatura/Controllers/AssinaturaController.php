<?php

namespace PainelApp\assinatura\Controllers;

use Controller\Controller;

final class AssinaturaController extends Controller
{
    public function index()
    {
        return view('painel.assinatura.index');
    }
}
