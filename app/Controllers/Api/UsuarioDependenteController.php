<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\UsuarioDependente\Helper;
use App\Models\Api\UsuarioCliente\DeletarModel;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\UsuarioDependente\DependenteModel;
use App\Models\Api\UsuarioDependente\DependenteEntity;

final class UsuarioDependenteController extends Controller implements
    ControllerSalvarInterface,
    ControllerListarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Usuario = new DependenteModel($request);
        $dado = $Usuario->listar();

        return new Response(json: [
            'status' => 'sucesso',
            'dado'   => $dado
        ]);
    }

    public function postSalvar(Request $request): Response
    {
        $dado = $request->dado();

        $Usuario = new DependenteEntity($request);
        $Usuario->set(lista: $dado);
        $Usuario->salvar();

        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity($Usuario, lista: ['nome', 'email', 'cpf', 'status']),
            status: 201,
            criptografar: Helper::CRIPTOGRAFAR
        );
    }

    public function deleteDeletar(string $id): Response
    {
        validarUuid($id);

        $Usuario = new DeletarModel();
        $Usuario->uuid($id);
        $Usuario->deletar();

        return new Response(status: 204);
    }
}
