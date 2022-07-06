<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\UsuarioDependente\Helper;
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
        $dado = $request->dadoDecode(
            chavePrivada: TOKEN['app']->chave_privada,
            descriptografar: Helper::CRIPTOGRAFAR
        );

        $Usuario = new DependenteEntity($request);
        $Usuario->set(lista: $dado);
        $Usuario->salvar();

        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity($Usuario, lista: ['nome', 'email', 'cpf']),
            status: 201,
            criptografar: Helper::CRIPTOGRAFAR
        );
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
