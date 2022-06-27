<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\UsuarioCliente\DeletarModel;
use App\Controllers\Api\Interface\ListarInterface;
use App\Controllers\Api\Interface\SalvarInterface;
use App\Controllers\Api\Interface\DeletarInterface;
use App\Models\Api\UsuarioDependente\DependenteModel;
use App\Models\Api\UsuarioDependente\DependenteEntity;

final class UsuarioDependenteController extends Controller implements
    SalvarInterface,
    ListarInterface,
    DeletarInterface
{
    public function getListar(Request $request)
    {
        $Usuario = new DependenteModel($request);
        $dado = $Usuario->listar();

        return new Response(json: [
            'status' => 'sucesso',
            'dado' => $dado
        ]);
    }

    public function postSalvar(Request $request)
    {
        $Usuario = new DependenteEntity($request);
        $Usuario->set(lista: $request->dado());
        $Usuario->salvar();

        return new Response(json: [
            'status' => 'sucesso',
            'dado' => pegarPropriedadeDaEntity($Usuario, $request, remover: ['usuario'])
        ], status: 201);
    }

    public function deleteDeletar(string $id)
    {
        validarUuid($id);

        $Usuario = new DeletarModel();
        $Usuario->id($id);
        $Usuario->deletar();

        return new Response(status: 204);
    }
}
