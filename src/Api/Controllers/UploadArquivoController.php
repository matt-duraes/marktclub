<?php

namespace ApiController;

use Erro\Excecao;
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
    /**
     * @param  Request  $request
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Arquivo = new ArquivoModel();
        $lista = $Arquivo->buscarArquivos($request->pagina, $request->pesquisa, $request->grupo);

        return mensagemSucesso($lista);
    }

    /**
     * @param  string  $id
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $Arquivo = new ArquivoEntity();
        $Arquivo->uuid($id);

        return mensagemSucesso(
            pegarPropriedadeDaEntity($Arquivo, lista: ['id', 'nome', 'extensao', 'link'])
        );
    }

    /**
     * @param  Request  $request
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Grupo = new GrupoEntity();
        $Grupo->uuid($request->grupo);

        $Arquivo = new ArquivoEntity(
            arquivo: $request->getFiles('arquivo'),
            Grupo: $Grupo
        );
        $Arquivo->salvar();

        return mensagemSucesso(
            pegarPropriedadeDaEntity($Arquivo, lista: [
                'id',
                'equipe',
                'nome',
                'extensao',
                'tamanho',
                'largura',
                'altura',
                'link' => 'arquivo',
                'data_criacao'
            ]),
            status: 201
        );
    }

    /**
     * @param  Request  $request
     * @param  string   $id
     * @return Response
     * @throws Excecao
     */
    public function putAtualizar(Request $request, string $id): Response
    {
        $Arquivo = new ArquivoEntity();
        $Arquivo->uuid($id);

        if (!$request->vazio('grupo')) {
            $Grupo = new GrupoEntity();
            $Grupo->uuid($request->grupo);
            $Arquivo->Grupo = $Grupo;
        }

        $Arquivo->set(
            lista: $request->lista([
                'nome'
            ], false)
        );
        $Arquivo->salvar();

        return new Response(status: 204);
    }

    /**
     * @param  string  $id
     * @return Response
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        $Arquivo = new ArquivoEntity();
        $Arquivo->uuid($id);
        $Arquivo->destruir();

        return new Response(status: 204);
    }
}
