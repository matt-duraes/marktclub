<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Modules\Botao;
use Controller\Controller;
use App\Models\Api\Demanda\TrabalhoEntity;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerAtualizarInterface;

final class DemandaTrabalhoController extends Controller implements
    ControllerSalvarInterface,
    ControllerAtualizarInterface
{
    public function postSalvar(Request $request): Response
    {
        $Trabalho = new TrabalhoEntity(
            tarefa: $request->tarefa
        );
        $Trabalho->salvar();

        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Trabalho,
                lista: [
                    'id', 'tempo_trabalho', 'tempo_total', 'data_criacao'
                ]
            ),
            201
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Trabalho = new TrabalhoEntity();
        $Trabalho->id($id);
        $Trabalho->set(lista: $request->dado());
        $Trabalho->salvar();

        return new Response(status: 204);
    }
}
