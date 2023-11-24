<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Modules\Botao;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\Geral\Status;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\ComunicacaoLogin\BannerModel;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\ComunicacaoLogin\BannerEntity;
use System\Interface\ControllerAtualizarInterface;

final class ComunicacaoLoginController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getBuscar(string $id): Response
    {
        $BannerEntity = new BannerEntity();
        $BannerEntity->uuid($id);
        return $this->retornoSucesso($BannerEntity);
    }

    public function postSalvar(Request $request): Response
    {
        $BannerEntity = new BannerEntity();
        $BannerEntity->set(lista: $request->dado());
        $BannerEntity->salvar();

        return $this->retornoSucesso($BannerEntity, 201);
    }

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

    public function getListar(Request $request): Response
    {
        $BannerModel = new BannerModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Data($request->dataInicio),
            new Data($request->dataFinal),
            new Status($request->status),
            new Botao($request->publicado),
            $request->empresa,
            $request->titulo
        );
        return mensagemSucesso($BannerModel->listarDados());
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $BannerEntity = new BannerEntity();
        $BannerEntity->uuid($id);
        $BannerEntity->set(lista: $request->dado());
        $BannerEntity->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $BannerEntity = new BannerEntity();
        $BannerEntity->uuid($id);
        $BannerEntity->destruir();

        return new Response(status: 204);
    }
}
