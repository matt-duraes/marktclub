<?php

namespace App\Controllers\Site;

use App\Models\Site\Cupom\ListarModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;

final class CupomController extends Controller
{
    /**
     * @param  Request      $request
     * @param  string|null  $pesquisa
     *
     * @return Response
     * @throws Excecao
     */
    public function buscar(Request $request, string $pesquisa = null): Response
    {
        if ($pesquisa) {
            return $this->index($pesquisa);
        }
        if (empty($request->pesquisa)) {
            return new Response(url: route('cupom.index'));
        }
        return new Response(url: route('cupom.buscar') . '/' . strSlug($request->pesquisa));
    }

    /**
     * @param  string|null  $pesquisa
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
                'lista'        => (new ListarModel())->listarDados(),
                'parceiroTipo' => 'cupom'
            ]
        );
    }

    /**
     * @param  string  $url
     *
     * @return Response
     * @throws Excecao
     */
    public function detalhe(string $url): Response
    {
        return view(
            'cupom.detalhe',
            [
                'url' => $url
            ]
        );
    }
}
