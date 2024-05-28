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
        $dados = [
            'saldo'          => 1000,
            'extrato_compra' => [
                0 => [
                    'parceiro_loja' => 'Casas Bahia',
                    'valor_compra'  => 1000,
                    'pontos_ganhos' => 100,
                    'status_compra' => 'sucesso'
                ],
                1 => [
                    'parceiro_loja' => 'CG móveis',
                    'valor_compra'  => 400,
                    'pontos_ganhos' => 40,
                    'status_compra' => 'analise'
                ],
                2 => [
                    'parceiro_loja' => 'Kabum',
                    'valor_compra'  => 1200,
                    'pontos_ganhos' => 120,
                    'status_compra' => 'bloqueado'
                ]
            ],
            'extrato_saque' => [
                0 => [
                    'pontos_solicitados' => 1000,
                    'status_resgate'     => 'analise',
                    'valor_dinheiro'     => 100
                ],
                1 => [
                    'pontos_solicitados' => 1500,
                    'valor_dinheiro'     => 150,
                    'status_resgate'     => 'bloqueado'
                ],
                2 => [
                    'pontos_solicitados' => 1800,
                    'valor_dinheiro'     => 180,
                    'status_resgate'     => 'sucesso'
                ],
            ]
        ];
        return view('cashback.extrato', [
            'menu'           => 'extrato_silium',
            'saldo'          => $dados['saldo'],
            'extrato_saque'  => $dados['extrato_saque'],
            'extrato_compra' => $dados['extrato_compra'],

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
        $SolicitacaoResgate = (new SiliumModel())->solicitarDeposito($request->dado());
        ppe($SolicitacaoResgate);
    }
}
