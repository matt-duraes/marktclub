<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Models\Site\Loja\MapaModel;
use App\Models\Site\Loja\BuscaModel;
use App\Models\Site\Loja\ListarModel;
use App\Models\Site\Loja\DetalheModel;
use App\Models\Site\Loja\RelacionadoModel;
use App\Models\Site\Loja\LojaModel;

use Helpers\ApiHelper;
use Helpers\ListaHelper;

final class LojaController extends Controller
{
    /**
     * @param  Request      $request
     * @param  string|null  $pesquisa
     *
     * @return Response
     * @throws Excecao
     */

    public function busca(Request $request, string $pesquisa = null): Response
    {
        $Busca = new BuscaModel($request, $pesquisa);
        if ($pesquisa) {
            return $this->index($request, $Busca);
        }
        return new Response(url: $Busca->url());
    }

    /**
     * @param  Request          $request
     * @param  BuscaModel|null  $Busca
     *
     * @return Response
     * @throws Excecao
     */
    public function index(Request $request, BuscaModel $Busca = null): Response
    {
        $dado = (new LojaModel())->listarDados();
        return view('loja.index', [
            'menu'         => 'loja',
            'banner'       => true,
            'Busca'        => $Busca instanceof BuscaModel ? $Busca : new BuscaModel($request),
            'lista'        => $dado,
            'parceiroTipo' => 'loja',
            'capa_desktop' => '',
            'banner'       => (new BannerModel())->loja(),
            'popupSimples' => true
        ]);
    }

    /**
     * @param  Request          $request
     * @param                   $url
     * @param  BuscaModel|null  $Busca
     *
     * @return Response
     * @throws Excecao
     */
    public function detalhe(Request $request, $url = null, BuscaModel $Busca = null): Response
    {
        $dado = (new LojaModel())->buscarDados($url);
        return view('loja.detalhe', [
            'menu'         => 'loja',
            'url'          => $url,
            'Busca'        => $Busca instanceof BuscaModel ? $Busca : new BuscaModel($request),
            'dado'         => $dado,
            'lista' => (new LojaModel())->relacionado($dado->id),
            'parceiroTipo' => 'loja'
        ]);
    }

    /**
     * @param  string  $url
     *
     * @return Response
     * @throws Excecao
     */
    public function confirmar(string $url): Response
    {
        return view('loja.confirmar');
    }

    /**
     * @param  Request         $request
     * @param  MapaModel|null  $Busca
     *
     * @return Response
     * @throws Excecao
     */
    public function proxima(Request $request, MapaModel $Busca = null): Response
    {
        return view('loja.proxima', [
            'menu' => 'loja-proxima',
        ]);
    }

    /**
     * @param  Request  $request
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
     * @param  Request  $request
     *
     * @return Response
     */
    public function postBuscaMapa(Request $request): Response
    {
        return new Response(status: 201);
    }



    /**
     * @param  Request  $request
     *
     * @return Response
     */
    public function postFavorito(Request $request): Response
    {
        $uuid = $request->uuid;
        $acao = $request->acao;
        $loja = (new RelacionadoModel())->favoritar($uuid, $acao);
        return new Response(json: $loja);

    }
    /**
     * @return Response
     */
    public function melhorIdade(): Response
    {
        $categoria = ['alimentacao','saude', 'veiculo'];
        $alimentacaoTag = [
            'bares','restaurante','churrascarias','doces', 'sanduiches', 'suplementos', 'cafes'
        ];
        $veiculoTag = ['concessionarias','locadoras','pneus','oficinas'];
        $saudeTag = ['academia','visao','esportes','spas'];
        $estados = (new ListaHelper())->estado()->r();

        return view('loja.melhor_idade', [
            'alimentacaoTag' => $alimentacaoTag,
            'veiculoTag' => $veiculoTag,
            'saudeTag' => $saudeTag,
            'categoria' => $categoria,
            'estados' => $estados
        ]);
    }
}
