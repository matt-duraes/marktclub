<?php

namespace App\Controllers\Site;

use App\Models\Site\Cashback\ListarModel;
use App\Models\Site\Cashback\RelacionadoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;

final class CashbackController extends Controller
{
    /**
     * @param  Request      $request
     * @param  string|null  $pesquisa
     *
     * @return Response
     * @throws Excecao
     */
    public function buscar(Request $request, ?string $pesquisa = null): Response
    {
        if ($pesquisa) {
            return $this->index($pesquisa);
        }
        if (empty($request->pesquisa)) {
            return new Response(url: route('cashback.index'));
        }
        return new Response(url: route('cashback.buscar') . '/' . strSlug($request->pesquisa));
    }

    /**
     * @param  string|null  $pesquisa
     *
     * @return Response
     * @throws Excecao
     */
    public function index(string $pesquisa = null): Response
    {
        return view('cashback.index', [
            'menu'         => 'cashback',
            'lista'        => (new ListarModel())->listarDados(),
            'parceiroTipo' => 'cashback'
        ]);
    }

    /**
     *
     * @return Response
     * @throws Excecao
     */
    public function detalhe(): Response
    {
        return view('cashback.detalhe', [
            'menu'         => 'cashback',
            'lista'        => (new RelacionadoModel())->listarDados(),
            'parceiroTipo' => 'cashback'
        ]);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function extrato(): Response
    {
        return view('cashback.extrato', [
            'saldo' => 10000
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
