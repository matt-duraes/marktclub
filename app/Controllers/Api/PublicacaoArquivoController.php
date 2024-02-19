<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\PublicacaoArquivo\ArquivoModel;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\PublicacaoArquivo\ArquivoEntity;

final class PublicacaoArquivoController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Arquivo = new ArquivoModel();
        $Arquivo->set(lista: $request->dado());

        return mensagemSucesso($Arquivo->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Arquivo = new ArquivoEntity();
        $Arquivo->uuid($id);

        return $this->retornoPadrao(Arquivo: $Arquivo, status: 200);
    }

    public function postSalvar(Request $request): Response
    {
        $Arquivo = new ArquivoEntity();
        $Arquivo->set(lista: $request->dado());
        $Arquivo->salvar();

        return $this->retornoPadrao(Arquivo: $Arquivo, status: 201);
    }

    private function retornoPadrao(ArquivoEntity $Arquivo, int $status): Response
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Arquivo,
                lista: [
                    'titulo', 'texto', 'tipo', 'data_inicio', 'data_final', 'permissao_restrita',
                    'permissao_site', 'imagem', 'arquivo', 'publicado', 'status'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Arquivo = new ArquivoEntity();
        $Arquivo->uuid($id);
        $Arquivo->set(lista: $request->dado());
        $Arquivo->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Arquivo = new ArquivoEntity();
        $Arquivo->uuid($id);
        $Arquivo->destruir();

        return new Response(status: 204);
    }
}
