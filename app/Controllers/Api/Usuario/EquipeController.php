<?php

namespace App\Controllers\Api\Usuario;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\UsuarioEquipe\Helper;
use App\Models\Api\Trait\ValidarUsuarioTrait;
use App\Models\Api\UsuarioEquipe\EquipeModel;
use App\Models\Api\UsuarioEquipe\SelectModel;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\UsuarioEquipe\MudarEmpresaModel;

final class EquipeController extends Controller implements
    ControllerSalvarInterface,
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    use ValidarUsuarioTrait;

    public array $dadoRetorno = [];
    public bool $perfil = false;

    public function getBuscar(string $id): Response
    {
        $Usuario = new EquipeEntity(perfil: $this->perfil);
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
        $retorno = !empty($this->dadoRetorno) && $status === 200 ? $this->dadoRetorno : [
            'Empresa' => ['id', 'nome_fantasia'],
            'subempresa', 'perfil', 'nome', 'cpf', 'imagem', 'email_trabalho', 'email_pessoal', 'tipo',
            'telefone_pessoal', 'genero', 'data_nascimento', 'primeiro_acesso', 'mudar_senha', 'id_google',
            'id_facebook', 'marktclub', 'gerente', 'admin', 'telefone_trabalho', 'status', 'permissao'
        ];
        return mensagemSucesso(
            pegarPropriedadeDaEntity($Usuario, lista: $retorno),
            status: $status,
            criptografar: Helper::CRIPTOGRAFAR
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Usuario = new EquipeEntity(perfil: $this->perfil);
        $Usuario->uuid($id);
        $Usuario->set(lista: $request->dado());
        $Usuario->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Usuario = new EquipeEntity();
        $Usuario->uuid($id);
        $Usuario->destruir();

        return new Response(status: 204);
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

    public function putEmpresa(Request $request): Response
    {
        new MudarEmpresaModel($request->empresa);
        return new Response(status: 204);
    }
}
