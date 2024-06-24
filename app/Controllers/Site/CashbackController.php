<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Site\Loja\BuscarModel;
use App\Models\Site\Loja\FiltroModel;
use App\Models\Site\Cashback\SiliumModel;

final class CashbackController extends Controller
{
    /**
     * @param Request         $request
     * @param BuscaModel|null $Busca
     *
     * @return Response
     * @throws Excecao
     */
    public function index(Request $request): Response
    {
        $Filtro = new FiltroModel($request->dado());
        return view('loja.index', [
            'menu'   => 'cashback',
            'Busca'  => $Filtro,
            'tipo'   => 'cashback',
            'banner' => [],
            'todos'  => empty($request->dado()),
        ]);
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
        $Dado = new BuscarModel(url: $url);
        return view(
            'cashback.detalhe',
            [
                'menu'  => 'cashback',
                'dado'  => $Dado->buscarDados(),
                'tipo'  => 'cashback'
            ]
        );
    }

    /**CASHBACK SILIUM*/
    /**
     * @return Response
     * @throws Excecao
     */
    public function extrato(): Response
    {
        $dados = (new SiliumModel())->buscarDados();
        return view('cashback.extrato', [
            'menu'           => 'extrato_silium',
            'saldo'          => $dados['saldo'] ?? 0,
            'extratoCompra' => $dados['extrato_compra'],
            'extratoSaque' => $dados['extrato_saque']
        ]);
    }

    /**
     *
     *
     * @param  Request  $request
     * @return Response
     */
    public function postResgatarCashback(Request $request): Response
    {
        $dado = (new SiliumModel())->solicitarDeposito($request->dado());
        return mensagemSucesso($dado, 201);

    }
}
