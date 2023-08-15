<?php

namespace App\Controllers\Site;

use App\Helpers\ClubeApiHelper;
use Controller\Controller;

final class PromocaoController extends Controller
{
    public function index()
    {
        $promocoes = ((new ClubeApiHelper()))
        ->json([
            'tipo'       => 'promocao',
            'pagina'     => 1,
            'quantidade' => 20,
        ])
        ->get('/publicidade')
        ->object();
        return view('promocao.index', [
            'promocoes'    => $promocoes->dado->lista,
            'menu'         => 'promocao',
            'tituloPagina' => 'Promoções',
            'lista'        => [1, 2, 3, 4, 5, 6]
        ]);
    }
}
