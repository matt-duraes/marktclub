<?php

namespace ApiController;

use Http\Request;
use Http\Response;
use Controller\Controller;
use ApiModel\Endereco\EnderecoModel;
use ApiModel\Endereco\EnderecoEntity;
use ApiModel\Endereco\EstruturaModel;
use ApiModel\Endereco\PrincipalModel;
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
    public function getPrincipal(Request $request): Response
    {
        $Endereco = new PrincipalModel(
            vinculo: $request->vinculo,
            local_principal: $request->local_principal,
            local_secundario: $request->local_secundario,
            latitude: $request->latitude,
            longitude: $request->longitude,
        );
        return mensagemSucesso($Endereco->endereco);
    }

    public function getEstrutura(Request $request)
    {
        $Endereco = new EstruturaModel(
            vinculo: $request->vinculo,
            local_principal: $request->local_principal,
            local_secundario: $request->local_secundario,
            pais: $request->pais,
            estado: $request->estado
        );

        return mensagemSucesso($Endereco->estrutura);
    }

    public function getListar(Request $request): Response
    {
        $Endereco = new EnderecoModel();
        $Endereco->set(lista: $request->dado());
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
                    'titulo', 'cep', 'logradouro', 'complemento', 'referencia',
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
