<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\UsuarioCliente\ClienteModel;
use App\Models\Api\UsuarioCliente\DeletarModel;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Controllers\Api\Interface\BuscarInterface;
use App\Controllers\Api\Interface\ListarInterface;
use App\Controllers\Api\Interface\SalvarInterface;
use App\Controllers\Api\Interface\DeletarInterface;
use App\Controllers\Api\Interface\AtualizarInterface;

final class UsuarioClienteController extends Controller implements
    SalvarInterface,
    ListarInterface,
    BuscarInterface,
    AtualizarInterface,
    DeletarInterface
{
    public function getBuscar(string $id)
    {
        validarUuid($id);

        $Usuario = new ClienteEntity();
        $Usuario->buscar([
            ['cod', $id],
            ['status', 'in', [1, 2, 3, 5]]
        ]);

        return $this->mensagemSucesso($Usuario);
    }
    private function mensagemSucesso(ClienteEntity $Usuario)
    {
        return mensagemSucesso(pegarPropriedadeDaEntity(
            $Usuario,
            lista: [
                'id', 'nome', 'siape', 'cpf', 'rg', 'email_trabalho', 'email_pessoal', 'email_funcional',
                'telefone_trabalho', 'telefone_pessoal', 'estado_civil', 'genero', 'imagem', 'data_nascimento',
                'matricula', 'endereco_cep', 'endereco_logradouro', 'endereco_numero', 'endereco_complemento',
                'endereco_bairro', 'endereco_cidade', 'endereco_estado', 'primeiro_acesso', 'possui_senha',
                'mudar_senha', 'situacao', 'contrato_siape', 'trabalho_empresa', 'trabalho_cargo', 'tipo_pagamento',
                'pagamento', 'trabalho_data_inicio', 'mensagem', 'status',
            ],
            chave: TOKEN['app']->chave_publica
        ));
    }

    public function getListar(Request $request)
    {
        $Usuario = new ClienteModel($request);
        $dado = $Usuario->listar();

        return new Response(json: [
            'status' => 'sucesso',
            'dado' => $dado
        ]);
    }
    public function postDownload(Request $request)
    {
        $Usuario = new ClienteModel($request);
        $dado = $Usuario->download();

        return new Response(json: [
            'status' => 'sucesso',
            'dado' => $dado
        ], status: 201);
    }

    public function postSalvar(Request $request)
    {
        $Usuario = new ClienteEntity($request);
        $Usuario->set(lista: $request->dado());
        $Usuario->salvar();

        $dado = $request->dado();
        $dado = array_merge(['id' => $Usuario->cod], $dado);
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
        validarUuid($id);

        $Usuario = new ClienteEntity($request);
        $Usuario->buscar([
            ['cod', $id],
            ['status', 'in', [1, 2, 3, 5]]
        ]);

        $Usuario->set(lista: $request->dado());
        $Usuario->salvar();

        return new Response(status: 204);
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
