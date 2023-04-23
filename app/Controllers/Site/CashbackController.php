<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\models\Site\Cashback\ListarModel;
use App\models\Site\Cashback\RelacionadoModel;

final class CashbackController extends Controller
{
    public function index(?string $pesquisa = null)
    {
        return view('cashback.index', [
            'menu' => 'cashback',
            'lista' => (new ListarModel())->listarDados(),
            'parceiroTipo' => 'cashback'
        ]);
    }

    public function buscar(Request $request, ?string $pesquisa = null)
    {
        if ($pesquisa) {
            return $this->index($pesquisa);
        }
        if (empty($request->pesquisa)) {
            return new Response(url: route('cashback.index'));
        }
        return new Response(url: route('cashback.buscar') . '/' . strSlug($request->pesquisa));
    }

    public function detalhe(string $url)
    {
        return view('cashback.detalhe', [
            'menu' => 'cashback',
            'lista' => (new RelacionadoModel())->listarDados(),
            'parceiroTipo' => 'cashback'
        ]);
    }

    public function extrato()
    {
        return view('cashback.extrato', [
            'saldo' => 10000
        ]);
    }

    public function abrirResgateCashback()
    {
        return view('cashback.extrato.modal');
    }
}
