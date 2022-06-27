<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\UsuarioEquipe\EquipeModel;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
use App\Controllers\Api\Interface\BuscarInterface;
use App\Controllers\Api\Interface\ListarInterface;
use App\Controllers\Api\Interface\SalvarInterface;
use App\Controllers\Api\Interface\DeletarInterface;
use App\Controllers\Api\Interface\AtualizarInterface;

final class UsuarioEquipeController extends Controller implements
    SalvarInterface,
    ListarInterface,
    BuscarInterface,
    AtualizarInterface,
    DeletarInterface
{
    public function getBuscar(string $id)
    {
        if (empty($id)) {
            mensagemStatus(404);
        }

        $Usuario = new EquipeEntity();
        $Usuario->id($id);

        return new Response(json: [
            'status' => 'sucesso',
            'dado' => $Usuario->retorno()
        ]);
    }

    public function getListar(Request $request)
    {
        $Usuario = new EquipeModel($request);
        $dado = $Usuario->listar();

        return new Response(json: [
            'status' => 'sucesso',
            'dado' => $dado
        ]);
    }

    public function postSalvar(Request $request)
    {
        $Usuario = new EquipeEntity($request);
        $Usuario->set(lista: $request->dado());
        $Usuario->salvar();

        $dado = $request->dado();
        $dado = array_merge(['id' => $Usuario->id], $dado);
        if (array_key_exists('senha', $dado)) {
            $dado['senha'] = true;
        }

        return new Response(json: [
            'status' => 'sucesso',
            'dado' => $dado
        ], status: 201);
    }

    public function putAtualizar(Request $request, string $id)
    {
        if (empty($id)) {
            mensagemStatus(404);
        }

        $Usuario = new EquipeEntity($request);
        $Usuario->id($id);

        $Usuario->set(lista: $request->dado());
        $Usuario->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id)
    {
        if (empty($id)) {
            mensagemStatus(404);
        }

        $Usuario = new EquipeEntity();
        $Usuario->id($id);
        $Usuario->destruir();

        return new Response(status: 204);
    }

    public function postValidarSenha(Request $request)
    {
        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi definido.');
        }
        $id = TOKEN['usuario']->get('id');
        if (empty($id)) {
            mensagemStatus(404);
        } else if (empty($request->senha)) {
            mensagemErro('Campo obrigatório!', 'O campo senha é obrigatório.');
        }

        $Equipe = new EquipeEntity();
        $Equipe->buscar([
            ['id', $id]
        ]);

        if ($Equipe->senha->validarSenha($request->senha)) {
            return mensagemSucesso(['senha' => true]);
        }
        mensagemErro('Senha inválida!', 'Verifique a senha digitada e tente novamente.');
    }
}
