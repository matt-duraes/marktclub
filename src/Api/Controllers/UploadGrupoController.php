<?php

namespace ApiController;

use ApiModel\Upload\GrupoEntity;
use ApiModel\Upload\GrupoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerSalvarInterface;

final class UploadGrupoController extends Controller implements
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    /**
     * @param  string   $id
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $Grupo = new GrupoEntity();
        $Grupo->uuid($id);

        return mensagemSucesso(
            pegarPropriedadeDaEntity($Grupo, lista: [
                'id',
                'equipe',
                'nome',
                'extensao',
                'privado'
            ])
        );
    }

    /**
     * @param  Request  $request
     * @return Response
     * @throws Excecao
     */
    public function getValidar(Request $request): Response
    {
        $Grupo = new GrupoModel();
        $valido = $Grupo->validarGrupoAtual($request->raiz, $request->grupo);
        return mensagemSucesso(['valido' => $valido ? 'sim' : 'nao']);
    }

    /**
     * @param  Request  $request
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Grupo = new GrupoEntity(
            grupo: $request->grupo,
            nome: $request->nome
        );
        $Grupo->salvar();

        return mensagemSucesso([
            'id'   => $Grupo->id,
            'nome' => $Grupo->nome,
        ], status: 201);
    }

    /**
     * @param  Request  $request
     * @param  string   $id
     * @return Response
     * @throws Excecao
     */
    public function putAtualizar(Request $request, string $id): Response
    {
        $Grupo = new GrupoEntity();
        $Grupo->uuid($id);
        $Grupo->nome = $request->nome;
        $Grupo->salvar();

        return new Response(status: 204);
    }

    /**
     * @param  string   $id
     * @return Response
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        $Grupo = new GrupoEntity();
        $Grupo->uuid($id);
        $Grupo->destruir();
        return new Response(status: 204);
    }

    /**
     * @param  string   $id
     * @return Response
     * @throws Excecao
     */
    public function getPai(string $id): Response
    {
        $Grupo = new GrupoModel();
        $diretorio = $Grupo->pegarGrupoPai($id);
        return mensagemSucesso($diretorio);
    }

    /**
     * @param  string   $id
     * @return Response
     * @throws Excecao
     */
    public function getFilho(string $id): Response
    {
        $Grupo = new GrupoModel();
        $diretorio = $Grupo->listarTodaArvoreDiretorio($id);
        return mensagemSucesso($diretorio);
    }
}
