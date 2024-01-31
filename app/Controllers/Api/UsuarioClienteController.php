<?php

namespace App\Controllers\Api;

use Modules\Cpf;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Senha;
use Modules\Inteiro;
use Controller\Controller;
use App\Classes\UsuarioCliente\Helper;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Classes\ConstrutorClube\TipoAtivacao;
use App\Models\Api\UsuarioCliente\AppleModel;
use App\Models\Api\UsuarioCliente\ClienteModel;
use App\Models\Api\UsuarioCliente\DeletarModel;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\UsuarioCliente\DownloadModel;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\DownloadPrivado\ArquivoEntity;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\UsuarioCliente\Ativar\AtivarModel;
use App\Models\Api\UsuarioCliente\Ativar\BuscarModel;
use App\Models\Api\UsuarioCliente\Senha\AlterarSenhaModel;
use App\Models\Api\UsuarioCliente\Senha\EnviarCodigoModel;
use App\Models\Api\UsuarioCliente\Senha\ValidarCodigoModel;

final class UsuarioClienteController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        validarUuid($id);

        $Usuario = new ClienteEntity();
        $Usuario->buscar([
            ['cod', $id],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ]);

        return $this->retornoSucesso($Usuario);
    }

    /**
     * @param ClienteEntity $Usuario
     * @param int           $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(ClienteEntity $Usuario, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($Usuario, lista: [
                'Empresa' => ['id', 'nome_fantasia'],
                'subempresa', 'nome', 'siape', 'cpf', 'rg', 'email_trabalho', 'email_pessoal', 'email_funcional',
                'telefone_trabalho', 'telefone_pessoal', 'estado_civil', 'genero', 'imagem',
                'data_nascimento', 'matricula', 'federacao', 'endereco_cep', 'endereco_logradouro', 'endereco_numero',
                'endereco_complemento', 'endereco_bairro', 'endereco_cidade', 'endereco_estado',
                'primeiro_acesso', 'possui_senha', 'mudar_senha', 'situacao', 'contrato_siape',
                'trabalho_empresa', 'trabalho_cargo', 'tipo_pagamento', 'pagamento',
                'trabalho_data_inicio', 'mensagem', 'pagamento', 'grupo', 'lead', 'origem', 'status', 'data_criacao'
            ]),
            $status,
            Helper::CRIPTOGRAFAR
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Usuario = new ClienteModel($request);
        $dado = $Usuario->listarDados();
        $dado->lista = criptografarDado($dado->lista, Helper::CRIPTOGRAFAR, lista: true);
        return mensagemSucesso($dado);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postDownload(Request $request): Response
    {
        $Usuario = new DownloadModel($request);
        $Download = new ArquivoEntity($Usuario->download(), $request->usuario);
        $Download->salvar();
        return mensagemSucesso([
            'id' => $Download->id
        ], 201);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Usuario = new ClienteEntity($request);
        $Usuario->set(lista: $request->dado());
        $Usuario->salvar();
        return $this->retornoSucesso($Usuario, 201);
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return Response
     * @throws Excecao
     */
    public function putAtualizar(Request $request, string $id): Response
    {
        validarUuid($id);

        $Usuario = new ClienteEntity($request);
        $Usuario->buscar([
            ['cod', $id],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ]);

        $Usuario->set(lista: $request->dado());
        $Usuario->salvar();
        return new Response(status: 204);
    }

    public function postImagem(Request $request): Response
    {
        $Usuario = new ClienteEntity();
        $Usuario->uuid($request->id);
        $Usuario->imagem_arquivo = $request->getFiles('arquivo');
        $Usuario->salvar();

        return mensagemSucesso([
            'id'     => $Usuario->id,
            'imagem' => $Usuario->imagem
        ], status: 201, criptografar: ['imagem']);
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        validarUuid($id);

        $Usuario = new DeletarModel();
        $Usuario->uuid($id);
        $Usuario->deletar();

        return new Response(status: 204);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function postApple(): Response
    {
        new AppleModel();
        return mensagemSucesso([
            'id' => uuid()
        ], 201);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postAtivar(Request $request): Response
    {
        $Ativar = new BuscarModel(
            $request->valor,
            $request->empresa,
            new TipoAtivacao($request->chave),
            new TipoUsuario($request->tipo_usuario)
        );
        return mensagemSucesso([
            'id'   => uuid(),
            'hash' => $Ativar->pegarHash(),
            'cpf'  => $Ativar->pegarCpf()->numero()
        ], 201);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function putAtivar(Request $request): Response
    {
        new AtivarModel($request);
        return new Response(status: 204);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getSenha(Request $request): Response
    {
        $Usuario = new EnviarCodigoModel(
            $request->empresa,
            new Cpf($request->cpf)
        );
        return mensagemSucesso([
            'id'      => uuid(),
            'usuario' => $Usuario->id
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSenha(Request $request): Response
    {
        $Usuario = new ValidarCodigoModel(
            $request->usuario,
            new Inteiro($request->codigo)
        );
        return mensagemSucesso([
            'id'   => uuid(),
            'hash' => $Usuario->hash
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function putSenha(Request $request): Response
    {
        new AlterarSenhaModel(
            new Senha($request->senha),
            $request->usuario,
            $request->hash,
        );
        return new Response(status: 204);
    }

    public function postValidarSenha(Request $request)
    {
        $id = TOKEN['usuario']->id;

        $senha = $request->senha;
        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi definido.');
        } elseif (empty($id)) {
            mensagemStatus(404);
        } elseif (empty($senha)) {
            mensagemErro('Campo obrigatório!', 'O campo senha é obrigatório.');
        }

        $Usuario = new ClienteEntity();
        $Usuario->buscar([
            ['id', $id]
        ]);

        if ($Usuario->senha->validarSenha($senha)) {
            return mensagemSucesso(['senha' => true]);
        }

        mensagemErro('Senha inválida!', 'A senha informada é inválida.');
    }
}
