<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Controller\Controller;
use App\Helpers\ClubeApiHelper;
use App\Classes\ParceiroLoja\Tipo;
use App\Models\Site\Loja\BuscarModel;
use App\Models\Site\Loja\FiltroModel;
use App\Models\Site\Loja\ListarModel;
use App\Models\Site\Loja\DeclaracaoModel;
use App\Classes\ParceiroLoja\Procedimento;
use App\Models\Site\Loja\ChequeBonusModel;
use App\Models\Site\Loja\SolicitacaoModel;
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
        try {
            $Filtro = new FiltroModel($request->dado());
            return new Response(url: $Filtro->link);
        } catch (\Throwable) {
            return new Response(route('loja.index'));
        }

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
        $Filtro = new FiltroModel($request->dado());
        return view('loja.index', [
            'menu'   => 'loja',
            'Busca'  => $Filtro,
            'mapa'   => $Filtro->mapa ?? false,
            'todos'  => empty($request->dado()),
            'banner' => []
        ]);
    }

    public function postListar(Request $request)
    {
        $Filtro = new FiltroModel($request->dado());
        $Lista = new ListarModel(
            tipo: new Tipo($request->tipo),
            Filtro: $Filtro
        );
        return mensagemSucesso($Lista->listarDados());
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
        $Dado = new BuscarModel(url: $url);
        $dado = $Dado->buscarDados();

        if ($this->verificaSeSamsung($dado->id)) {
            return new Response(url: route('samsung.index'));
        }

        return view('loja.detalhe', [
            'menu'         => 'loja',
            'dado'         => $dado,
            'telefone'     => [],
            'email'        => [],
            'tipo'         => 'loja',
            'Busca'        => (new FiltroModel([])),
            'procedimento' => new Procedimento()
        ]);
    }

    private function verificaSeSamsung(string $uuid)
    {
        return in_array(
            $uuid,
            [
                '56e660e57971ece155e37a9a86bd32b7', '6857d871f6f0f11b5b866872f0612b99',
                '53e78ad604c8df17b89d3f38921c66d4'
            ]
        );
    }

    public function postRelacionado(Request $request)
    {
        $Dado = new ListarModel(id: $request->id);
        return mensagemSucesso($Dado->listarRelacionado());
    }

    public function getConfirmar(string $url): Response
    {
        return view('loja.confirmar', [
            'dado'         => (new BuscarModel($url))->buscarDados(),
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

    public function chequeBonus(string $id)
    {
        return view('loja.cheque_bonus', ['id' => $id]);
    }

    public function postChequeBonus(Request $request)
    {
        $ChequeBonus = new ChequeBonusModel($request);
        $ChequeBonus->salvar();

        return mensagemSucesso([], status: 201);
    }

    public function postDeclaracao(Request $request)
    {
        $Declaracao = new DeclaracaoModel(
            parceiro: $request->parceiro,
            modelo: $request->modelo,
            versao: $request->versao
        );
        $Declaracao->salvar();

        return mensagemSucesso([], status: 201);
    }

    public function postIndicar(Request $request)
    {
        new SolicitacaoModel(
            nome: $request->nome,
            telefone: $request->telefone,
            email: $request->email,
            mensagem: $request->mensagem,
        );

        return mensagemSucesso([], status: 201);
    }
}
