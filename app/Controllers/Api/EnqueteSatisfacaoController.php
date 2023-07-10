<?php

namespace App\Controllers\Api;

use App\Models\Api\EnqueteSatisfacao\EnqueteEntity;
use App\Models\Api\EnqueteSatisfacao\EnqueteModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

final class EnqueteSatisfacaoController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface
{
    /**
     * @param string $id
    *
     * @return Response
     * @throws Excecao
    */
    public function getBuscar(string $id): Response
    {
        $EnqueteEntity = new EnqueteEntity();
        $EnqueteEntity->uuid($id);
        return $this->retornoSucesso($EnqueteEntity);
    }

    /**
     * @param EnqueteEntity $enqueteEntity
     * @param int           $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(EnqueteEntity $enqueteEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $enqueteEntity,
                lista: [
                    'navegar', 'procura', 'suporte', 'comentario',
                    'atendimento', 'sistemas', 'status', 'data_criacao'
                ]
            ),
            $status
        );
    }

    /**
     * @param EnqueteEntity $enqueteEntity
    *
     * @return Response
     * @throws Excecao
    */
    public function postSalvar(Request $request): Response
    {
        $Enquete = new EnqueteEntity();
        $Enquete->set(lista: $request->dado());
        $Enquete->salvar();

        return $this->retornoSucesso($Enquete, 201);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Enquete = new EnqueteModel($request);
        return mensagemSucesso($Enquete->listarDados());
    }
}
