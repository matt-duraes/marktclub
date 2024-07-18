<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Site\Site\IndicacaoModel as SiteIndicacaoModel;
use App\Models\Site\Pesquisa\SalvarModel as SalvarPesquisaModel;

final class SiteController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function getPesquisa(): Response
    {
        return view('pesquisa.index');
    }

    /**
     *
     * @return Response
     * @throws Excecao
     */
    public function postPesquisa(Request $request): Response
    {
        new SalvarPesquisaModel($request);
        return mensagemSucesso([], status: 201);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function indiqueAmigo(): Response
    {
        return view('indicar_amigo.index', [
            'tituloPagina' => 'Indique para um amigo',
            'menu'         => 'indicar_amigo'
        ]);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function postIndicarAmigo(Request $request): Response
    {
        $dado = (new SiteIndicacaoModel())->indicarAmigo($request);
        return mensagemSucesso($dado);
    }

    public function abrirModalEnquetePopup($id = null): Response
    {
        return view('popup.enquete');
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function abrirModalPopupImagem(): Response
    {
        return view('popup.imagem');
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function regulamento_campanha(): Response
    {
        return view('regulamento.campanha');
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function getAjuda(): Response
    {
        return view('ajuda.index');
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function getIndiqueParceiro(): Response
    {
        return view('indicar_parceiro.index');
    }
}
