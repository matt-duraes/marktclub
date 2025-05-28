<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\ParceiroLoja\Ordem;
use App\Models\Site\Saude\HomeModel;
use App\Classes\EnqueteMercado\Gasto;
use App\Models\Site\Loja\FiltroModel;
use App\Models\Site\Loja\ListarModel;
use App\Classes\EnqueteMercado\Padrao;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Models\Site\Loja\PesquisaModel;
use App\Classes\EnqueteMercado\Produtos;
use App\Models\Site\Pesquisa\Utilizacao;
use App\Classes\EnqueteMercado\Fidelidade;
use App\Classes\EnqueteMercado\Frequencia;
use App\Classes\EnqueteMercado\Experiencia;
use App\Classes\EnqueteMercado\Importancia;
use App\Models\Site\Comunicacao\BannerModel;

final class IndexController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        $Filtro = new FiltroModel([]);
        return view('index', [
            'menu'            => 'home',
            'Busca'           => $Filtro,
            'banner'          => (new BannerModel())->home(),
            'plano_saude'     => (new HomeModel())->valor,
            'mostrarPesquisa' => (new Utilizacao())->mostrarPesquisa ? 'sim' : 'nao',
        ]);
    }

    public function postBuscar()
    {
        $LojaFavorita = new ListarModel(
            Filtro: new FiltroModel([
                'quantidade' => 3,
                'favorito'   => 'sim',
            ])
        );
        $MaisUtilizada = new ListarModel(
            tipo: (new TipoLoja(TipoLoja::LOJA)),
            Filtro: new FiltroModel([
                'quantidade' => 3,
                'acessado'   => 'sim',
            ])
        );
        $LojaNova = new ListarModel(
            tipo: (new TipoLoja(TipoLoja::LOJA)),
            Filtro: new FiltroModel([
                'quantidade' => 3,
                'ordem'      => (new Ordem(Ordem::MAIS_NOVO))->valor(),
            ])
        );
        return mensagemSucesso([
            'favorito' => $LojaFavorita->listarDados()->lista ?? [],
            'acessado' => $MaisUtilizada->listarDados()->lista ?? [],
            'novo'     => $LojaNova->listarDados()->lista ?? [],
        ]);
    }

    public function getPesquisaUtilizacao(): Response
    {
        $programaFidelidade = (new Fidelidade())->select();
        $produtosProcurados = (new Produtos())->select();
        $tvSmart = (new Gasto())->select();
        $opcaoProdutoMarca = (new Importancia())->select();
        $acreditaEmCashback = (new Padrao())->select();
        $frequenciaCashback = (new Frequencia())->select();
        $resgateCashback = (new Padrao())->select();
        $sobreParcerias = (new Padrao())->select();
        $suaExperiencia = (new Experiencia())->select();
        $voceIndicaria = (new Padrao())->select();

        return view('pesquisa_utilizacao', [
            'programaFidelidade' => $programaFidelidade,
            'produtosProcurados' => $produtosProcurados,
            'tvSmart'            => $tvSmart,
            'opcaoProdutoMarca'  => $opcaoProdutoMarca,
            'acreditaEmCashback' => $acreditaEmCashback,
            'frequenciaCashback' => $frequenciaCashback,
            'resgateCashback'    => $resgateCashback,
            'sobreParcerias'     => $sobreParcerias,
            'suaExperiencia'     => $suaExperiencia,
            'voceIndicaria'      => $voceIndicaria,
        ]);
    }

    public function postPesquisaUtilizacao(Request $request)
    {
        $dado = (new PesquisaModel())->salvar($request);
        return mensagemSucesso([], status: 201);
    }
}
