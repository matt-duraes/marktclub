<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Botao;
use Modules\Inteiro;
use Helpers\ApiHelper;
use Helpers\ListaHelper;
use Controller\Controller;
use App\Helpers\ClubeApiHelper;
use App\Models\Site\BannerModel;
use App\Classes\ParceiroLoja\Tipo;
use App\Classes\ParceiroLoja\Ordem;
use App\Models\Site\Loja\BuscarModel;
use App\Models\Site\Loja\FiltroModel;
use App\Models\Site\Loja\ListarModel;
use App\Classes\ParceiroLoja\Procedimento;
use App\Classes\SolicitacaoVoucher\Tipo as SolicitacaoVoucherTipo;

final class LojaController extends Controller
{
    /**
     * @param Request     $request
     * @param string|null $pesquisa
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(Request $request): Response
    {
        $Filtro = new FiltroModel($request);
        return new Response(url: $Filtro->link);
    }

    /**
     * @param Request         $request
     * @param BuscaModel|null $Busca
     *
     * @return Response
     * @throws Excecao
     */
    public function index(Request $request): Response
    {
        $Lista = new ListarModel(
            pagina: new Inteiro($request->pagina),
            quantidade: new Inteiro(24),
            favorito: new Botao($request->favorito),
            tipo: new Tipo(Tipo::LOJA),
            ordem: new Ordem(!empty($request->ordem) ? $request->ordem : 'favorito')
        );

        $Filtro = new FiltroModel($request);
        return view('loja.index', [
            'menu'   => 'loja',
            'Busca'  => $Filtro,
            'lista'  => $Lista->listarDados(),
            'banner' => $Filtro->existe ? [] : (new BannerModel())->loja()
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
            'dado'         => $Dado->buscarDados(),
            'endereco'     => [],
            'telefone'     => [],
            'email'        => [],
            'tipo'         => 'loja',
            'lista'        => $Lista->listarDados(),
            'procedimento' => new Procedimento()
        ]);
    }

    public function voucher(string $url)
    {
        $dado = (new ClubeApiHelper())
            ->validar(status: 404)
            ->body([
                'id'      => $url,
                'tipo'    => SolicitacaoVoucherTipo::LOJA,
                'usuario' => sessao('USUARIO.id')
            ])
            ->post('/solicitacao-voucher')
            ->object()->dado;

        return view('loja.voucher', [
            'dado' => $dado
        ]);
    }

    public function postSubcategoria(Request $request)
    {
        $dado = (new ApiHelper(scope: 'parceiro_subcategoria:listar'))
            ->json([
                'categoria' => $request->categoria,
                'titulo'    => 'Escolha uma categoria'
            ])
            ->get('/parceiro-subcategoria/select')
            ->array();

        return mensagemSucesso($dado['dado'] ?? []);
    }

    public function postFavorito(Request $request): Response
    {
        (new ApiHelper(scope: 'parceiro_favorito:salvar'))
            ->validar(mensagem: 'Erro ao salvar favorito, por favor, tente novamente.', retorno: false)
            ->body(['parceiro' => $request->id])
            ->post('/parceiro-favorito')
            ->object()->dado;

        return mensagemSucesso([], status: 201);
    }

    public function deleteFavorito(string $id)
    {
        (new ApiHelper(scope: 'parceiro_favorito:deletar'))
            ->validar('Erro ao remover favorito, por favor, tente novamente.', retorno: false)
            ->delete('/parceiro-favorito/' . $id);
        return new Response(status: 204);
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
