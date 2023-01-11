<?php

namespace ApiController;

use Http\Request;
use Http\Response;
use Controller\Controller;
use ApiModel\Upload\GrupoModel;
use ApiModel\Upload\GrupoEntity;
use App\Controllers\Api\Interface\BuscarInterface;
use App\Controllers\Api\Interface\SalvarInterface;
use App\Controllers\Api\Interface\DeletarInterface;
use App\Controllers\Api\Interface\AtualizarInterface;

final class UploadGrupoController extends Controller implements
    BuscarInterface,
    SalvarInterface,
    AtualizarInterface,
    DeletarInterface
{
    public function getBuscar(string $id)
    {
        $Grupo = new GrupoEntity();
        $Grupo->id($id);

        return mensagemSucesso(
            pegarPropriedadeDaEntity($Grupo, lista: [
                'id', 'equipe', 'nome', 'extensao', 'privado'
            ])
        );
    }

    public function getValidar(Request $request)
    {
        $Grupo = new GrupoModel();
        $valido = $Grupo->validarGrupoAtual($request->raiz, $request->grupo);
        return mensagemSucesso(['valido' => $valido ? 'sim' : 'nao']);
    }

    public function postSalvar(Request $request)
    {
        $Grupo = new GrupoEntity(
            grupo: $request->grupo,
            nome: $request->nome
        );
        $Grupo->salvar();

        return mensagemSucesso([
            'id' => $Grupo->id,
            'nome' => $Grupo->nome,
        ], status: 201);
    }

    public function putAtualizar(Request $request, string $id)
    {
        $Grupo = new GrupoEntity();
        $Grupo->id($id);
        $Grupo->nome = $request->nome;
        $Grupo->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id)
    {
        $Grupo = new GrupoEntity();
        $Grupo->id($id);
        $Grupo->destruir();
        return new Response(status: 204);
    }

    public function getPai(string $id)
    {
        $Grupo = new GrupoModel();
        $diretorio = $Grupo->pegarGrupoPai($id);
        return mensagemSucesso($diretorio);
    }

    public function getFilho(string $id)
    {
        $Grupo = new GrupoModel();
        $diretorio = $Grupo->listarTodaArvoreDiretorio($id);
        return mensagemSucesso($diretorio);
    }
}
