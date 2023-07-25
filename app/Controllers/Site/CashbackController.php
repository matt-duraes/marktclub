<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\ParceiroCashback\Ordem;
use App\Models\Site\Cashback\ListarModel;

final class CashbackController extends Controller
{
    /**
     * @param string|null $pesquisa
     *
     * @return Response
     * @throws Excecao
     */
    public function index(Request $request): Response
    {
        $Lista = new ListarModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            pesquisa: $request->pesquisa,
            categoria: $request->categoria,
            ordem: new Ordem($request->ordem)
        );
        return view(
            'cashback.index',
            [
                'menu'  => 'cashback',
                'lista' => $Lista->listarDados()
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
        $Lista = new ListarModel(
            pagina: new Pagina(1),
            quantidade: new Quantidade(3)
        );
        return view(
            'cashback.detalhe',
            [
                'menu'         => 'cashback',
                'lista'        => $Lista->listarDados()
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
