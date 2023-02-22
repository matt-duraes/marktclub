<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\UsuarioGrupo\GrupoModel;
use App\Models\Api\UsuarioGrupo\GrupoEntity;
use App\Models\Api\UsuarioGrupo\SelectModel;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerSelectInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class UsuarioGrupoController extends Controller implements
    ControllerSalvarInterface,
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface,
    ControllerSelectInterface
{

    public function getSelect(Request $request): Response
    {
        $Grupo = new SelectModel($request);
        return mensagemSucesso($Grupo->listarDados());
    }
    public function getBuscar(string $id): Response
    {
        validarUuid($id);

        $Grupo = new GrupoEntity();
        $Grupo->id($id);

        return $this->retornoSucesso($Grupo);
    }

    public function getListar(Request $request): Response
    {
        $Grupo = new GrupoModel($request);
        $dado = $Grupo->listarDados();

        return mensagemSucesso($dado);
    }

    public function postSalvar(Request $request): Response
    {
        $Grupo = new GrupoEntity();
        $Grupo->set(lista: $request->dado());
        $Grupo->salvar();

        return $this->retornoSucesso($Grupo, 201);
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        validarUuid($id);

        $Grupo = new GrupoEntity();
        $Grupo->id($id);

        $Grupo->set(lista: $request->dado());
        $Grupo->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        validarUuid($id);

        $Grupo = new GrupoEntity();
        $Grupo->id($id);
        $Grupo->destruir();

        return new Response(status: 204);
    }

    private function retornoSucesso(GrupoEntity $Grupo, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                $Grupo,
                lista: [
                    'id', 'indice', 'titulo', 'data_criacao', 'data_atualizacao', 'status'
                ],
            ),
            status: $status
        );
    }
}
