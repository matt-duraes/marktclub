<?php

namespace ApiController;

use Http\Request;
use Http\Response;
use Controller\Controller;
use ApiModel\Upload\GrupoEntity;
use ApiModel\Upload\ArquivoModel;
use ApiModel\Upload\ArquivoEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class UploadArquivoController extends Controller implements
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface,
    ControllerBuscarInterface
{
    public function getListar(Request $request)
    {
        $Arquivo = new ArquivoModel();
        $lista = $Arquivo->buscarArquivos($request->pagina, $request->pesquisa, $request->grupo);

        return mensagemSucesso($lista);
    }

    public function getBuscar(string $id)
    {
        $Arquivo = new ArquivoEntity();
        $Arquivo->id($id);

        return mensagemSucesso(
            pegarPropriedadeDaEntity($Arquivo, lista: ['id', 'nome', 'extensao', 'link'])
        );
    }

    public function postSalvar(Request $request)
    {
        $Grupo = new GrupoEntity();
        $Grupo->id($request->grupo);

        $Arquivo = new ArquivoEntity(
            arquivo: $request->_FILES('arquivo'),
            Grupo: $Grupo
        );
        $Arquivo->salvar();

        return mensagemSucesso(
            pegarPropriedadeDaEntity($Arquivo, lista: [
                'id', 'equipe', 'nome', 'extensao', 'tamanho', 'largura', 'altura', 'link' => 'arquivo', 'data_criacao'
            ]),
            status: 201
        );
    }
    public function putAtualizar(Request $request, string $id)
    {
        $Arquivo = new ArquivoEntity();
        $Arquivo->id($id);

        if (!$request->vazio('grupo')) {
            $Grupo = new GrupoEntity();
            $Grupo->id($request->grupo);
            $Arquivo->Grupo = $Grupo;
        }

        $Arquivo->set(lista: $request->lista([
            'nome'
        ], false));
        $Arquivo->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id)
    {
        $Arquivo = new ArquivoEntity();
        $Arquivo->id($id);
        $Arquivo->destruir();

        return new Response(status: 204);
    }
}
