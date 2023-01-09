<?php

namespace ApiController;

use Http\Request;
use Controller\Controller;
use ApiModel\Upload\GrupoEntity;
use ApiModel\Upload\ArquivoModel;
use ApiModel\Upload\ArquivoEntity;
use App\Controllers\Api\Interface\ListarInterface;
use App\Controllers\Api\Interface\SalvarInterface;

final class UploadArquivoController extends Controller implements
    ListarInterface,
    SalvarInterface
{
    public function getListar(Request $request)
    {
        $Arquivo = new ArquivoModel();
        $lista = $Arquivo->buscarArquivos($request->pagina, $request->pesquisa, $request->grupo);

        return mensagemSucesso($lista);
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
                'id', 'equipe', 'nome', 'extensao', 'tamanho', 'largura', 'altura', 'arquivo', 'data_criacao'
            ]),
            status: 201
        );
    }
}
