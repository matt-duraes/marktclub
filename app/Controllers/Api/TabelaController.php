<?php

namespace App\Controllers\Api;

use App\Classes\TabelaUsuario\Ordem;
use App\Classes\TabelaUsuario\Status;
use App\Classes\TabelaUsuario\Tipo;
use App\Models\Api\TabelaUsuario\TabelaEntity;
use App\Models\Api\TabelaUsuario\TabelaModel;
use Http\Request;
use Http\Response;
use Controller\Controller;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerSalvarInterface;

final class TabelaController extends Controller implements
    ControllerSalvarInterface
{
    public function getListar(Request $request): Response
    {
        $Tabela = new TabelaModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            ordem: new Ordem($request->ordem),
            status: new Status($request->status),
            tipo: new Tipo($request->tipo),
            empresa: $request->empresa,
            data_de: $request->data_de,
            data_ate: $request->data_ate,
        );
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
