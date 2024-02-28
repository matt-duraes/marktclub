<?php

namespace App\Controllers\Api\Votacao;

use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\Votacao\Resposta\RespostaModel;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\Votacao\Resposta\RespostaEntity;

final class RespostaController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Votacao = new RespostaModel();
        $Votacao->set(lista: $request->dado());

        return mensagemSucesso($Votacao->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Resposta = new RespostaEntity();
        $Resposta->uuid($id);

        return $this->retornoPadrao(Resposta: $Resposta, status: 200);
    }

    public function postSalvar(Request $request): Response
    {
        $Resposta = new RespostaEntity();
        $Resposta->set(lista: $request->dado());
        $Resposta->salvar();

        return $this->retornoPadrao(Resposta: $Resposta, status: 201);
    }

    private function retornoPadrao(RespostaEntity $Resposta, int $status): Response
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Resposta,
                lista: [
                    'titulo', 'texto', 'tipo', 'data_inicio', 'data_final', 'status'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Resposta = new RespostaEntity();
        $Resposta->uuid($id);
        $Resposta->set(lista: $request->dado());
        $Resposta->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Resposta = new RespostaEntity();
        $Resposta->uuid($id);
        $Resposta->destruir();

        return new Response(status: 204);
    }
}
