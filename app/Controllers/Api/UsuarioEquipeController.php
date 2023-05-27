<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\UsuarioEquipe\Helper;
use App\Models\Api\UsuarioEquipe\EquipeModel;
use App\Models\Api\UsuarioEquipe\SelectModel;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\UsuarioEquipe\MudarEmpresaModel;

final class UsuarioEquipeController extends Controller implements
    ControllerSalvarInterface,
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getBuscar(string $id): Response
    {
        $Usuario = new EquipeEntity();
        $Usuario->uuid($id);

        return $this->retornoSucesso($Usuario);
    }

    public function getListar(Request $request): Response
    {
        $Usuario = new EquipeModel($request);
        $dado = $Usuario->listarDados();
        $dado->lista = criptografarDado(
            dado: $dado->lista,
            criptografia: helper::CRIPTOGRAFAR,
            lista: true
        );
        return mensagemSucesso($dado);
    }

    public function postSalvar(Request $request): Response
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
                    'Empresa' => ['id', 'nome_fantasia'],
                    'perfil', 'nome', 'cpf', 'imagem', 'email_trabalho', 'email_pessoal', 'telefone_trabalho',
                    'telefone_pessoal', 'genero', 'data_nascimento', 'primeiro_acesso', 'mudar_senha', 'id_google',
                    'id_facebook', 'marktclub', 'gerente', 'admin', 'status', 'permissao'
                ],
            ),
            status: $status,
            criptografar: Helper::CRIPTOGRAFAR
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Usuario = new EquipeEntity();
        $Usuario->uuid($id);
        $Usuario->set(lista: $request->dado());
        $Usuario->salvar();

        return new Response(status: 204);
    }
    public function putEmpresa(Request $request): Response
    {
        new MudarEmpresaModel($request->empresa);
        return new Response(status: 204);
    }

    public function postImagem(Request $request)
    {
        $Usuario = new EquipeEntity();
        $Usuario->uuid($request->id);
        $Usuario->imagem_arquivo = $request->getFiles('imagem');
        $Usuario->salvar();

        return mensagemSucesso([
            'id' => $Usuario->id,
            'imagem' => $Usuario->imagem
        ], status: 201, criptografar: ['imagem']);
    }

    public function deleteDeletar(string $id): Response
    {
        $Usuario = new EquipeEntity();
        $Usuario->uuid($id);
        $Usuario->destruir();

        return new Response(status: 204);
    }

    public function postValidarSenha(Request $request)
    {
        $id = TOKEN['usuario']->get('id');

        $senha = $request->senha;
        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi definido.');
        } elseif (empty($id)) {
            mensagemStatus(404);
        } elseif (empty($senha)) {
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
        $Equipe = new SelectModel($request);
        return mensagemSucesso($Equipe->listarSelect());
    }

    public function getPerfil()
    {
        $Equipe = new SelectModel();
        return mensagemSucesso($Equipe->listarPerfil());
    }
}
