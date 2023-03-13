<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\UsuarioCliente\Helper;
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

final class UsuarioClienteController extends Controller implements
    ControllerSalvarInterface,
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
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

    public function getListar(Request $request): Response
    {
        $Usuario = new ClienteModel($request);
        $dado = $Usuario->listarDados();

        $dado->lista = criptografarDado(
            dado: $dado->lista,
            criptografia: Helper::CRIPTOGRAFAR,
            lista: true
        );

        return mensagemSucesso($dado);
    }

    public function postDownload(Request $request)
    {
        $Usuario = new DownloadModel($request);
        $dado = $Usuario->download();
        ppe($dado);
        $Download = new ArquivoEntity(
            $dado,
            $request->usuario
        );
        $Download->salvar();

        return mensagemSucesso([
            'id' => $Download->id
        ], status: 201);
    }

    public function postSalvar(Request $request): Response
    {
        $Usuario = new ClienteEntity($request);
        $Usuario->set(lista: $request->dado());
        $Usuario->salvar();

        return $this->retornoSucesso($Usuario, 201);
    }

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

    public function deleteDeletar(string $id): Response
    {
        validarUuid($id);

        $Usuario = new DeletarModel();
        $Usuario->id($id);
        $Usuario->deletar();

        return new Response(status: 204);
    }

    private function retornoSucesso(ClienteEntity $Usuario, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                $Usuario,
                lista: [
                    'Empresa' => ['id', 'nome_fantasia'],
                    'nome', 'siape', 'cpf', 'rg', 'email_trabalho', 'email_pessoal', 'email_funcional',
                    'telefone_trabalho', 'telefone_pessoal', 'estado_civil', 'genero', 'imagem', 'data_nascimento',
                    'matricula', 'federacao', 'endereco_cep', 'endereco_logradouro', 'endereco_numero',
                    'endereco_complemento', 'endereco_bairro', 'endereco_cidade', 'endereco_estado',
                    'primeiro_acesso', 'possui_senha', 'mudar_senha', 'situacao', 'contrato_siape',
                    'trabalho_empresa', 'trabalho_cargo', 'tipo_pagamento', 'pagamento',
                    'trabalho_data_inicio', 'mensagem', 'pagamento', 'grupo', 'lead', 'origem', 'status'
                ]
            ),
            status: $status,
            criptografar: Helper::CRIPTOGRAFAR
        );
    }
}
