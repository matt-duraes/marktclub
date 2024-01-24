<?php

namespace App\Controllers\Api;

use App\Models\Api\TabelaUsuario\TabelaEntity;
use App\Models\Api\TabelaUsuario\TabelaModel;
use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerSalvarInterface;

final class TabelaController extends Controller implements
    ControllerSalvarInterface
{
    public function getListar(Request $request): Response
    {
        $Tabela = new TabelaModel($request);
        return mensagemSucesso($Tabela->listarDados());
    }

    public function postSalvar(Request $request): Response
    {
        $Tabela = new TabelaEntity(
            arquivoUpload: $request->getFiles('arquivo')
        );
        $Tabela->set(lista: $request->dado());
        $Tabela->salvar();

        return $this->retornoSucesso($Tabela);
    }

    private function retornoSucesso(TabelaEntity $Entity, int $status = 200)
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Entity,
                lista: [
                    'id', 'usuario', 'empresa', 'arquivo', 'erro', 'novo',
                    'atualizado', 'tipo', 'status', 'data_criacao', 'data_atualizacao'
                ],
            ),
            status: $status
        );
    }
}
