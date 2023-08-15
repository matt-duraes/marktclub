<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Site\Cupom\BuscaModel;
use App\Models\Site\Cupom\ListarModel;

final class CupomController extends Controller
{
    /**
     * @param Request     $request
     * @param string|null $pesquisa
     *
     * @return Response
     * @throws Excecao
     */
    public function buscar(Request $request): Response
    {
        if (empty($request->pesquisa)) {
            return new Response(url: route('cupom.index'));
        }
        return new Response(url: route('cupom.index') . '?pesquisa=' . $request->pesquisa);
    }

    /**
     * @param string|null $pesquisa
     *
     * @return Response
     * @throws Excecao
     */
    public function index(string $pesquisa = null): Response
    {
        return view(
            'cupom.index',
            [
                'menu'         => 'cupom',
                'banner'       => false,
                'pesquisa'     => $pesquisa,
                'lista'        => (new ListarModel())->listarDados($pesquisa),
                'parceiroTipo' => 'cupom'
            ]
        );
    }

    /**
     * @param string $url
     *
     * @return Response
     * @throws Excecao
     */
    public function detalhe(string $url): Response
    {
        $dado = (new BuscaModel())->listarDados($url);

        return view(
            'cupom.detalhe',
            [
                'url'  => $url,
                'dado' => $dado->lista
            ]
        );
    }
}
