<?php

namespace App\Controllers\Site;

use Helpers\ApiHelper;
use Controller\Controller;

final class FarmaciaController extends Controller
{
    public function index()
    {
        return view('farmacia.index', [
            'menu' => 'farmacia',
        ]);
    }
    public function detalhe()
    {
        return view('farmacia.detalhe', [
            'menu' => 'farmacia',
            'parceiro' => [1, 2, 3]
        ]);
    }
    public function carteirinha()
    {
        $Api = new ApiHelper('carteirinha:buscar');

        $carteira = $Api->get('/carteirinha/5595203c-f7b1-4211-9981-bf09eb236b35')->object();

        return view('farmacia.carteirinha', [
            'carteira' => $carteira ?? null
        ]);
    }

    public function tabela()
    {
        return view('farmacia.carteirinha');
    }
}
