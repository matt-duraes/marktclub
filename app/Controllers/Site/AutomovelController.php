<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Classes\ParceiroLoja\Tipo;
use App\Classes\ParceiroLoja\Ordem;
use App\Models\Site\Automovel\BuscarModel;
use App\Models\Site\Automovel\ListarModel;
use App\Models\Site\Loja\ListarModel as LojaModel;
use App\Models\Site\Automovel\SalvarIndicacaoModel;

final class AutomovelController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        $Listar = new LojaModel(
            tipo: new Tipo(Tipo::AUTOMOVEL),
            ordem: new Ordem(Ordem::TITULO_AZ)
        );

        return view('automovel.index', [
            'menu'   => 'automovel',
            'lista'  => $Listar->listarDados(),
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

    public function versao(string $url): Response
    {
        $Buscar = new BuscarModel($url);
        return view(
            arquivo: 'automovel.versao',
            var: [
                'menu' => 'automovel',
                'dado' => $Buscar->buscarDados(),
                'endereco' => []
            ]
        );
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function abrirModalModeloVoucher($url = null): Response
    {
        return view('automovel.detalheAutomovel.modalVoucher');
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function abrirModalModeloDeclaracao($url = null): Response
    {
        $perfil = 'titular';
        $default = $perfil == 'titular' ? '' : 'esconde';
        $esconde = $perfil == 'dependente' ? '' : 'esconde';

        return view('automovel.detalheAutomovel.modalDeclaracao', [
            'perfil'  => 'titular',
            'default' => $default,
            'esconde' => $esconde
        ]);
    }

    /**
     *
     * @return Response
     * @throws Excecao
     */
    public function postIndicacao(Request $request): Response
    {
        $indicacao = new SalvarIndicacaoModel($request);
        $indicacao = $indicacao->postSalvar();

        return new Response(json: [
            'status' => 'sucesso'
        ], status: 201);
    }
}
