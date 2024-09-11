<?php

namespace App\Controllers\Site;

use App\Models\Site\Automovel\BuscarModel;
use App\Models\Site\Automovel\ListarModel;
use App\Models\Site\Automovel\SolicitacaoModel;
use App\Models\Site\BannerModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;

final class AutomovelController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        return view('loja.index', [
            'menu'   => 'automovel',
            'tipo'   => 'automovel',
            'banner' => (new BannerModel())->automovel()
        ]);
    }

    /**
     * @param string $url Slug (URI) do Modelo
     *
     * @return Response
     * @throws Excecao
     */
    public function modelo(string $url): Response
    {
        return view('automovel.modelo', [
            'menu'  => 'automovel',
            'lista' => (new ListarModel($url))->listarDados()
        ]);
    }

    /**
     * @param string $loja   Slug (URI) da Loja/Parceiro
     * @param string $modelo Slug (URI) do Modelo
     *
     * @return Response
     * @throws Excecao
     */
    public function versao(string $loja, string $modelo): Response
    {
        return view('automovel.versao', [
            'menu'     => 'automovel',
            'dado'     => (new BuscarModel($loja, $modelo))->buscarDados(),
            'loja'     => $loja,
            'endereco' => []
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSolicitacao(Request $request): Response
    {
        $Solicitacao = new SolicitacaoModel(
            enderecoEstado: $request->endereco_estado,
            enderecoCidade: $request->endereco_cidade,
            montadora: $request->montadora,
            modelo: $request->modelo,
            versao: $request->versao,
            cor: $request->cor,
            mensagem: $request->mensagem
        );
        $Solicitacao->salvar();
        return mensagemSucesso([], status: 201);
    }
}
