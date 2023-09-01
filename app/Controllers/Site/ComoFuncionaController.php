<?php

namespace App\Controllers\Site;

use Http\Response;
use Helpers\ApiHelper;
use Controller\Controller;
use App\Classes\TextoClube\Tipo;

final class ComoFuncionaController extends Controller
{
    public function index(): Response
    {
        try {
            $lista = (new ApiHelper(scope: 'texto_clube:listar'))
            ->json([
                'empresa' => EMPRESA_ID,
                'pagina'  => 1,
                'tipo'    => Tipo::COMO_FUNCIONA,
                'status'  => 'ativo'
            ])
            ->get('/texto-clube')
            ->object()->dado->lista;
        } catch (\Throwable) {
            $lista = [];
        }

        return view('como_funciona.index', [
            'menu'  => 'como-funciona',
            'lista' => $lista
        ]);
    }

    public function detalhe(string $url): Response
    {
        $dado = (new ApiHelper(scope: 'texto_clube:buscar'))
            ->validar(status: 404)
            ->get('/texto-clube/' . $url)
            ->object()->dado;

        return view('como_funciona.detalhe', [
            'menu' => 'como-funciona',
            'dado' => $dado
        ]);
    }
}
