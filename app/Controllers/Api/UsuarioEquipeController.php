<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\UsuarioEquipe\Helper;
use App\Models\Api\UsuarioEquipe\EquipeModel;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class UsuarioEquipeController extends Controller implements
    ControllerSalvarInterface,
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getBuscar(string $id)
    {
        if (empty($id)) {
            mensagemStatus(404);
        }

        $Usuario = new EquipeEntity();
        $Usuario->id($id);

        return $this->retornoSucesso($Usuario);
    }

    public function getListar(Request $request)
    {
        $Usuario = new EquipeModel($request);
        $dado = $Usuario->listar();
        $dado->lista = criptografarDado($dado->lista, lista: helper::CRIPTOGRAFAR);
        return mensagemSucesso($dado);
    }

    public function postSalvar(Request $request)
    {
        $Usuario = new EquipeEntity();
        $Usuario->set(lista: $request->dado());
        $Usuario->salvar();

        return $this->retornoSucesso($Usuario, 201);
    }

    private function retornoSucesso(EquipeEntity $Usuario, int $status = 200)
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Usuario,
                lista: [
                    'id', 'Empresa', 'perfil', 'nome', 'cpf', 'imagem', 'email_trabalho', 'email_pessoal', 'telefone_trabalho',
                    'telefone_pessoal', 'genero', 'data_nascimento', 'primeiro_acesso', 'mudar_senha', 'id_google',
                    'id_facebook', 'gerente', 'admin', 'status', 'permissao'
                ],
            ),
            status: $status,
            criptografar: Helper::CRIPTOGRAFAR
        );
    }

    public function putAtualizar(Request $request, string $id)
    {
        validarUuid($id);
        $Usuario = new EquipeEntity();
        $Usuario->id($id);
        $Usuario->set(lista: $request->dado());
        $Usuario->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id)
    {
        validarUuid($id);

        $Usuario = new EquipeEntity();
        $Usuario->id($id);
        $Usuario->destruir();

        return new Response(status: 204);
    }

    public function postValidarSenha(Request $request)
    {
        $id = TOKEN['usuario']->get('id');

        $senha = $request->senha;
        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi definido.');
        } else if (empty($id)) {
            mensagemStatus(404);
        } else if (empty($senha)) {
            mensagemErro('Campo obrigatório!', 'O campo senha é obrigatório.');
        }

        $Equipe = new EquipeEntity();
        $Equipe->buscar([
            ['id', $id]
        ]);

        if ($Equipe->senha->validarSenha($senha)) {
            return mensagemSucesso(['senha' => true]);
        }
        mensagemErro('Senha inválida!', 'Verifique a senha digitada e tente novamente.');
    }

    public function getSelect(Request $request)
    {
        $Equipe = new EquipeModel();
        $select = $Equipe->pegarSelect('uuid', 'nome_real', [
            ['status', 1]
        ], titulo: $request->titulo);

        return mensagemSucesso($select);
    }
}
