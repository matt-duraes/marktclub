<?php

namespace App\Controllers\Api;

use ORM\Entity;
use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\ParceiroCashback\CashbackModel;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\ParceiroCashback\CashbackEntity;

final class ParceiroCashbackController extends Controller implements
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface
{
    public function getListar(Request $request): Response
    {
        $Cashback = new CashbackModel($request);
        return mensagemSucesso($Cashback->pegarRetorno());
    }
    public function getBuscar(string $id): Response
    {
        $Cashback = new CashbackEntity();
        $Cashback->uuid($id);

        return $this->retornoSucesso($Cashback);
    }
    public function postSalvar(Request $request): Response
    {
        $Cashback = new CashbackEntity();
        $Cashback->set(lista: $request->dado());
        $Cashback->salvar();

        return $this->retornoSucesso($Cashback, 201);
    }

    public function retornoSucesso(Entity $Entity, int $status = 200): Response
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Entity,
                lista: [
                    'titulo', 'texto_descricao', 'texto_restricao', 'texto_outro',
                    'imagem', 'comissao', 'comissao_minima', 'comissao_maxima', 'link_site',
                    'link_usuario', 'empresa', 'status'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Cashback = new CashbackEntity();
        $Cashback->uuid($id);
        $Cashback->set(lista: $request->dado());
        $Cashback->salvar();

        return new Response(status: 204);
    }
}
