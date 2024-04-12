<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Site\Loja\BuscarModel;
use App\Models\Site\Loja\FiltroModel;

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

    public function buscar(Request $request)
    {
        $pesquisa = $request->pesquisa;
        $categoria = $request->categoria;
        $ordem = $request->ordem;
        $uri = [];
        if (!empty($pesquisa)) {
            $uri[] = 'pesquisa=' . $pesquisa;
        }
        if (!empty($categoria)) {
            $uri[] = 'categoria=' . $categoria;
        }
        if (!empty($ordem)) {
            $uri[] = 'ordem=' . $ordem;
        }
        $uri = !empty($uri) ? '?' . implode('&', $uri) : '';
        return new Response(url: route('cashback.index') . $uri);
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
        $Dado = new BuscarModel($url);
        return view(
            'cashback.detalhe',
            [
                'menu'  => 'cashback',
                'dado'  => $Dado->buscarDados(),
                'tipo'  => 'cashback'
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
            'menu'    => 'extrato_silium',
            'saldo'   => 0,
        ]);
    }
}
