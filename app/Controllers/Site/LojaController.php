<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Botao;
use Modules\Inteiro;
use Helpers\ListaHelper;
use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Classes\ParceiroLoja\Ordem;
use App\Models\Site\Loja\BuscarModel;
use App\Models\Site\Loja\FiltroModel;
use App\Models\Site\Loja\ListarModel;

final class LojaController extends Controller
{
    /**
     * @param Request     $request
     * @param string|null $pesquisa
     *
     * @return Response
     * @throws Excecao
     */
    public function busca(Request $request, string $pesquisa = null): Response
    {
        $Busca = new FiltroModel($request, $pesquisa);
        if ($pesquisa) {
            return $this->index($request, $Busca);
        }
        return new Response(url: $Busca->url());
    }

    /**
     * @param Request         $request
     * @param BuscaModel|null $Busca
     *
     * @return Response
     * @throws Excecao
     */
    public function index(Request $request, FiltroModel $Busca = null): Response
    {
        $Lista = new ListarModel(
            pagina: new Inteiro($request->pagina),
            favorito: new Botao($request->favorito),
            ordem: new Ordem($request->ordem)
        );
        return view('loja.index', [
            'menu'         => 'loja',
            'banner'       => true,
            'Busca'        => $Busca instanceof FiltroModel ? $Busca : new FiltroModel($request),
            'lista'        => $Lista->listarDados(),
            'parceiroTipo' => 'loja',
            'banner'       => (new BannerModel())->loja(),
            'popupSimples' => true
        ]);
    }

    /**
     * @param Request         $request
     * @param                 $url
     * @param BuscaModel|null $Busca
     *
     * @return Response
     * @throws Excecao
     */
    public function detalhe(string $url): Response
    {
        $Dado = new BuscarModel($url);
        $Lista = new ListarModel(
            quantidade: new Inteiro(3),
            ordem: new Ordem(Ordem::RANDOMICO)
        );
        return view('loja.detalhe', [
            'menu'         => 'loja',
            'lista'        => $Lista->listarDados(),
            'dado'         => $Dado->buscarDados(),
            'parceiroTipo' => 'loja'
        ]);
    }

    /**
     * @param string $url
     *
     * @return Response
     * @throws Excecao
     */
    public function confirmar(string $url): Response
    {
        return view('loja.confirmar');
    }

    /**
     * @param Request        $request
     * @param MapaModel|null $Busca
     *
     * @return Response
     * @throws Excecao
     */
    public function proxima(): Response
    {
        return view('loja.proxima', [
            'menu' => 'loja-proxima',
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function abrirModal(Request $request): Response
    {
        return view('loja.geral.modal');
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function abrirModalIndicacao(): Response
    {
        return view('loja.geral.modalIndicacao', [
            // 'tipo'      => $tipo,
        ]);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function abrirMapaModal(): Response
    {
        return view('loja.proxima.modal', [
            'menu' => 'loja-proxima'
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function postBuscaMapa(Request $request): Response
    {
        return new Response(status: 201);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function postFavorito(Request $request): Response
    {
        $uuid = $request->uuid;
        $acao = $request->acao;
        $loja = []; //(new RelacionadoModel())->favoritar($uuid, $acao);
        return new Response(json: $loja);
    }

    /**
     * @return Response
     */
    public function melhorIdade(): Response
    {
        $categoria = ['alimentacao', 'saude', 'veiculo'];
        $alimentacaoTag = [
            'bares', 'restaurante', 'churrascarias', 'doces', 'sanduiches', 'suplementos', 'cafes'
        ];
        $veiculoTag = ['concessionarias', 'locadoras', 'pneus', 'oficinas'];
        $saudeTag = ['academia', 'visao', 'esportes', 'spas'];
        $estados = (new ListaHelper())->estado()->r();

        return view('loja.melhor_idade', [
            'alimentacaoTag' => $alimentacaoTag,
            'veiculoTag'     => $veiculoTag,
            'saudeTag'       => $saudeTag,
            'categoria'      => $categoria,
            'estados'        => $estados
        ]);
    }
}
