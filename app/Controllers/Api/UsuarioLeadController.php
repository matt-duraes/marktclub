<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\UsuarioLead\Status;
use App\Models\Api\UsuarioLead\LeadModel;
use App\Models\Api\UsuarioLead\LeadEntity;
use App\Controllers\Api\Interface\BuscarInterface;
use App\Controllers\Api\Interface\ListarInterface;
use App\Controllers\Api\Interface\SalvarInterface;
use App\Controllers\Api\Interface\DeletarInterface;
use App\Controllers\Api\Interface\AtualizarInterface;

final class UsuarioLeadController extends Controller implements
    SalvarInterface,
    ListarInterface,
    BuscarInterface,
    AtualizarInterface,
    DeletarInterface
{
    public function postSalvar(Request $request)
    {
        $Lead = new LeadEntity();
        $Lead->set(lista: $request->dado());
        $Lead->salvar();

        return mensagemSucesso(pegarPropriedadeDaEntity($Lead, request: $request, lista: ['status']), status: 201);
    }

    public function getListar(Request $request)
    {
        $Lead = new LeadModel($request);
        $dado = $Lead->listarDados();

        return mensagemSucesso($dado);
    }

    public function getBuscar(string $id)
    {
        validarUuid($id);

        $Lead = new LeadEntity;
        $Lead->id($id);

        return mensagemSucesso([
            'id' => $Lead->id,
            'nome' => $Lead->nome->nome(),
            'siape' => $Lead->siape,
            'cpf' => $Lead->cpf->numero(),
            'rg' => $Lead->rg,
            'genero' => $Lead->genero->genero(),
            'data_nascimento' => $Lead->data_nascimento->date(),
            'email_pessoal' => $Lead->email_pessoal->email(),
            'email_trabalho' => $Lead->email_trabalho->email(),
            'email_funcional' => $Lead->email_funcional->email(),
            'telefone_pessoal' => $Lead->telefone_pessoal->numero(),
            'telefone_trabalho' => $Lead->telefone_trabalho->numero(),
            'endereco_cep' => $Lead->endereco_cep->numero(),
            'endereco_logradouro' => $Lead->endereco_logradouro,
            'endereco_numero' => $Lead->endereco_numero,
            'endereco_complemento' => $Lead->endereco_complemento,
            'endereco_bairro' => $Lead->endereco_bairro,
            'endereco_cidade' => $Lead->endereco_cidade,
            'endereco_estado' => $Lead->endereco_estado->estado(),
            'lista_dependente' => $Lead->lista_dependente,
            'contrato_siape' => $Lead->contrato_siape,
            'trabalho_empresa' => $Lead->trabalho_empresa->indice(),
            'trabalho_cargo' => $Lead->trabalho_cargo->indice(),
            'trabalho_data_inicio' => $Lead->trabalho_data_inicio->date(),
            'status' => $Lead->status->indice(),
        ]);
    }

    public function putAtualizar(Request $request, string $id)
    {
        validarUuid($id);

        $Lead = new LeadEntity;
        $Lead->id($id);
        $Lead->status = new Status($request->status);
        $Lead->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id)
    {
        validarUuid($id);

        $Lead = new LeadEntity;
        $Lead->id($id);
        $Lead->destruir();

        return new Response(status: 204);
    }
}
