<?php

namespace App\Controllers\Api;

use App\Classes\UsuarioLead\Helper;
use App\Classes\UsuarioLead\Status;
use App\Models\Api\UsuarioLead\LeadEntity;
use App\Models\Api\UsuarioLead\LeadModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

final class UsuarioLeadController extends Controller implements
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
        $Lead = new LeadEntity();
        $Lead->uuid($id);
        return $this->retornoSucesso($Lead);
    }

    /**
     * @param LeadEntity $Lead
     * @param int        $status
     * @param bool       $vazio
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(LeadEntity $Lead, int $status = 200, bool $vazio = true): Response
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
        if (array_key_exists('lista_dependente', $dado)) {
            $dado['lista_dependente'] = criptografarDado(
                $dado['lista_dependente'],
                ['nome', 'documento_cpf', 'data_nascimento', 'genero'],
                lista: true
            );
        }
        return mensagemSucesso($dado, $status, Helper::CRIPTOGRAFAR);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Lead = new LeadModel($request);
        $dado = $Lead->listarDados();

        $dado->lista = criptografarDado(
            dado: $dado->lista,
            criptografia: Helper::CRIPTOGRAFAR,
            lista: true
        );

        return mensagemSucesso($dado);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $dado = $request->dado();

        $Lead = new LeadEntity();
        $Lead->set(lista: $dado);
        $Lead->salvar();

        return $this->retornoSucesso($Lead, 201, false);
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
        $Lead = new LeadEntity();
        $Lead->uuid($id);
        $Lead->status = new Status($request->status);
        $Lead->salvar();

        return new Response(status: 204);
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        $Lead = new LeadEntity();
        $Lead->uuid($id);
        $Lead->destruir();

        return new Response(status: 204);
    }
}
