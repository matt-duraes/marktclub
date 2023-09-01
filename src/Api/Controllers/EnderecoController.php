<?php

namespace ApiController;

use Http\Request;
use Http\Response;
use Controller\Controller;
use Modules\EnderecoEstado;
use System\Classes\Endereco\Tipo;
use System\Classes\Endereco\Local;
use System\Classes\Endereco\Ordem;
use ApiModel\Endereco\EnderecoModel;
use ApiModel\Endereco\EnderecoEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class EnderecoController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Endereco = new EnderecoModel(
            vinculo: $request->vinculo,
            tipo: new Tipo($request->tipo),
            local: new Local($request->local),
            pais: $request->pais,
            cidade: $request->cidade,
            estado: new EnderecoEstado($request->estado),
            ordem: new Ordem($request->ordem),
        );
        return mensagemSucesso($Endereco->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Endereco = new EnderecoEntity();
        $Endereco->uuid($id);

        return $this->retornoPadrao($Endereco);
    }

    public function postSalvar(Request $request): Response
    {
        $Endereco = new EnderecoEntity();
        $Endereco->set(lista: $request->dado());
        $Endereco->salvar();

        return $this->retornoPadrao($Endereco, status: 201);
    }

    private function retornoPadrao(EnderecoEntity $Endereco, int $status = 200): Response
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Endereco,
                lista: [
                    'titulo', 'telefone', 'cep', 'logradouro', 'complemento', 'referencia',
                    'numero', 'bairro', 'cidade', 'estado', 'pais', 'latitude', 'longitude', 'principal'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Endereco = new EnderecoEntity();
        $Endereco->uuid($id);
        $Endereco->set(lista: $request->dado());
        $Endereco->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Endereco = new EnderecoEntity();
        $Endereco->uuid($id);
        $Endereco->destruir();

        return new Response(status: 204);
    }
}
