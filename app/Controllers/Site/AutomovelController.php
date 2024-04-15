<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Classes\ParceiroLoja\Ordem;
use App\Models\Site\Loja\FiltroModel;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Models\Site\Automovel\BuscarModel;
use App\Models\Site\Automovel\ListarModel;
use App\Models\Site\Automovel\SolicitacaoModel;
use App\Models\Site\Loja\ListarModel as LojaModel;

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
     * @param string $url
     *
     * @return Response
     * @throws Excecao
     */
    public function modelo(string $url): Response
    {
        return view(
            'automovel.modelo',
            [
                'menu'  => 'automovel',
                'lista' => (new ListarModel($url))->listarDados(),
            ]
        );
    }

    public function versao(string $loja, string $url): Response
    {
        $Buscar = new BuscarModel($url);
        return view(
            arquivo: 'automovel.versao',
            var: [
                'menu'     => 'automovel',
                'dado'     => $Buscar->buscarDados(),
                'loja'     => $loja,
                'endereco' => []
            ]
        );
    }

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
