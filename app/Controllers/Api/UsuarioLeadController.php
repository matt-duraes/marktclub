<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\UsuarioLead\Helper;
use App\Classes\UsuarioLead\Status;
use App\Models\Api\UsuarioLead\LeadModel;
use App\Models\Api\UsuarioLead\LeadEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class UsuarioLeadController extends Controller implements
    ControllerSalvarInterface,
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function postSalvar(Request $request): Response
    {
        $dado = $request->dado();

        $Lead = new LeadEntity();
        $Lead->set(lista: $dado);
        $Lead->salvar();

        return $this->retornoSucesso($Lead, 201, false);
    }

    public function getListar(Request $request): Response
    {
        $Lead = new LeadModel($request);
        $dado = $Lead->listarDados();
        $dado->lista = criptografarDado($dado->lista, Helper::CRIPTOGRAFAR);

        return mensagemSucesso($dado);
    }

    public function getBuscar(string $id): Response
    {
        validarUuid($id);

        $Lead = new LeadEntity;
        $Lead->id($id);

        return $this->retornoSucesso($Lead);
    }

    private function retornoSucesso(LeadEntity $Lead, int $status = 200, bool $vazio = true)
    {
        $dado = pegarPropriedadeDaEntity(
            $Lead,
            lista: [
                'id', 'nome', 'siape', 'cpf', 'rg', 'genero', 'data_nascimento', 'email_pessoal', 'email_trabalho',
                'email_funcional', 'telefone_pessoal', 'telefone_trabalho', 'endereco_cep', 'endereco_logradouro',
                'endereco_numero', 'endereco_complemento', 'endereco_bairro', 'endereco_cidade', 'endereco_estado',
                'lista_dependente', 'contrato_siape', 'trabalho_empresa', 'trabalho_cargo', 'trabalho_data_inicio',
                'status', 'data_criacao', 'origem', 'cnpj_trabalho'
            ],
            null: $vazio,
            empty: $vazio
        );
        return mensagemSucesso($dado, status: $status, criptografar: Helper::CRIPTOGRAFAR);
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        validarUuid($id);

        $Lead = new LeadEntity;
        $Lead->id($id);
        $Lead->status = new Status($request->status);
        $Lead->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        validarUuid($id);

        $Lead = new LeadEntity;
        $Lead->id($id);
        $Lead->destruir();

        return new Response(status: 204);
    }
}
