<?php

namespace App\Controllers\Api;

use Erro\Excecao;
use Http\Request;
use Modules\Data;
use Http\Response;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\Geral\Status;
use App\Classes\ComunicacaoLogin\Ordem;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\ComunicacaoLogin\BannerModel;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\ComunicacaoLogin\BannerEntity;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\ComunicacaoLogin\BannerClubeEntity;

final class ComunicacaoLoginController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $BannerEntity = new BannerEntity();
        $BannerEntity->uuid($id);
        return $this->retornoSucesso($BannerEntity);
    }

    public function getClube(Request $request): Response
    {
        $Banner = new BannerClubeEntity($request->empresa);
        return $this->retornoSucesso($Banner);
    }

    /**
     * @param BannerEntity $BannerEntity
     * @param int          $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(BannerEntity $BannerEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($BannerEntity, lista: [
                'titulo', 'arquivo_1', 'arquivo_2', 'arquivo_3', 'empresa', 'padrao',
                'data_inicio', 'data_fim', 'status'
            ]),
            $status
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $BannerEntity = new BannerEntity();
        $BannerEntity->set(lista: $request->dado());
        $BannerEntity->salvar();
        return $this->retornoSucesso($BannerEntity, 201);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $BannerModel = new BannerModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            new Botao($request->publicado),
            $request->empresa,
            $request->titulo_banner,
            new Data($request->dataInicio),
            new Data($request->dataFinal),
            new Status($request->status)
        );
        return mensagemSucesso($BannerModel->listarDados());
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return Response
     * @throws Excecao
     */
    public function putAtualizar(Request $request, string $id): Response
    {
        $BannerEntity = new BannerEntity();
        $BannerEntity->uuid($id);
        $BannerEntity->set(lista: $request->dado());
        $BannerEntity->salvar();
        return new Response(status: 204);
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        $BannerEntity = new BannerEntity();
        $BannerEntity->uuid($id);
        $BannerEntity->destruir();
        return new Response(status: 204);
    }
}
