<?php

namespace App\Controllers\Site;

use App\Models\Site\BannerModel;
use App\Models\Site\Loja\ListarModel;
use App\Models\Site\Turismo\CidadeAeroportoModel;
use App\Models\Site\Turismo\CidadeHotelModel;
use App\Models\Site\Turismo\SolicitaHotelModel;
use App\Models\Site\Turismo\SolicitaVooModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;

final class TurismoController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        return view('turismo.index', [
            'menu'         => 'turismo',
            'banner'       => (new BannerModel())->turismo(),
            'carro'        => (new BannerModel())->turismoCarro(),
            'lista'        => (new ListarModel())->listarDados(),
            'parceiroTipo' => 'turismo',
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getAeroporto(Request $request): Response
    {
        $Cidade = new CidadeAeroportoModel($request);
        $cidade = $Cidade->getDado();

        return new Response(json: [
            'status' => 'sucesso',
            'dado'   => $cidade
        ], status: 201);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getHotel(Request $request): Response
    {
        $Cidade = new CidadeHotelModel($request);
        $cidade = $Cidade->getDado();

        return new Response(json: [
            'status' => 'sucesso',
            'dado'   => $cidade
        ], status: 201);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSolicitaVoo(Request $request): Response
    {
        $SolicitaVoo = new SolicitaVooModel($request);
        $solicitaVoo = $SolicitaVoo->postDado();

        if (empty($solicitaVoo)) {
            mensagemErro(
                $solicitaVoo->erro->titulo ?? 'Erro!',
                $solicitaVoo->erro->mensagem ?? 'Ocorreu um erro ao solicitar seu voo',
            );
        }

        return new Response(json: [
            'status' => 'sucesso',
            'dado'   => $solicitaVoo
        ], status: 201);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSolicitaHotel(Request $request): Response
    {
        $SolicitaHotel = new SolicitaHotelModel($request);
        $solicitaHotel = $SolicitaHotel->postDado();

        if (empty($solicitaHotel)) {
            mensagemErro(
                $solicitaHotel->erro->titulo ?? 'Erro!',
                $solicitaHotel->erro->mensagem ?? 'Ocorreu um erro ao solicitar seu hotel',
            );
        }

        return new Response(json: [
            'status' => 'sucesso',
            'dado'   => $solicitaHotel
        ], status: 201);
    }
}
