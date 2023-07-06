<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Response;
use Controller\Controller;
use App\Models\Site\Cashback\ListarModel;
use App\Models\Site\Cashback\RelacionadoModel;

final class CashbackController extends Controller
{
    /**
     * @param string|null $pesquisa
     *
     * @return Response
     * @throws Excecao
     */
    public function index(string $pesquisa = null): Response
    {
        return view(
            'cashback.index',
            [
                'menu'  => 'cashback',
                'lista' => (new ListarModel())->listarDados(),
                'tipo'  => 'cashback'
            ]
        );
    }

    /**
     *
     * @param string $url
     *
     * @return Response
     * @throws Excecao
     */
    public function detalhe(string $url): Response
    {
        return view(
            'cashback.detalhe',
            [
                'menu'         => 'cashback',
                'lista'        => (new RelacionadoModel())->listarDados(),
                'tipo'         => 'cashback'
            ]
        );
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function extrato(): Response
    {
        return view('cashback.extrato', [
            'saldo'  => 10000
        ]);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function abrirResgateCashback(): Response
    {
        return view('cashback.extrato.modal');
    }
}
