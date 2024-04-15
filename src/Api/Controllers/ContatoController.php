<?php

namespace ApiController;

use Http\Request;
use Http\Response;
use Controller\Controller;
use ApiModel\Contato\ContatoModel;
use ApiModel\Contato\ContatoEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class ContatoController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Contato = new ContatoModel();
        $Contato->set(lista: $request->dado());
        return mensagemSucesso($Contato->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Contato = new ContatoEntity();
        $Contato->uuid($id);

        return $this->retornoPadrao($Contato);
    }

    public function postSalvar(Request $request): Response
    {
        $Contato = new ContatoEntity();
        $Contato->set(lista: $request->dado());
        $Contato->salvar();

        return $this->retornoPadrao($Contato, status: 201);
    }

    private function retornoPadrao(ContatoEntity $Contato, int $status = 200): Response
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Contato,
                lista: [
                    'titulo', 'nome', 'cpf', 'tipo', 'valor', 'whatsapp', 'principal'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Contato = new ContatoEntity();
        $Contato->uuid($id);
        $Contato->set(lista: $request->dado());
        $Contato->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Contato = new ContatoEntity();
        $Contato->uuid($id);
        $Contato->destruir();

        return new Response(status: 204);
    }
}
