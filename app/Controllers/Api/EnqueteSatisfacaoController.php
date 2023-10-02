<?php

namespace App\Controllers\Api;

use App\Classes\EnqueteSatisfacao\Ordem;
use App\Classes\EnqueteSatisfacao\Status;
use App\Models\Api\EnqueteSatisfacao\EnqueteEntity;
use App\Models\Api\EnqueteSatisfacao\EnqueteModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

final class EnqueteSatisfacaoController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
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
            pegarPropriedadeDaEntity($enqueteEntity, lista: [
                'navegar', 'procura', 'suporte', 'comentario',
                'atendimento', 'sistemas_clube', 'status', 'data_criacao'
            ]),
            $status
        );
    }

    /**
     * @param Request $request
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
        $Enquete = new EnqueteModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            new Status($request->status)
        );
        return mensagemSucesso($Enquete->listarDados());
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return Response
     * @throws Excecao
     */
    public function putAtualizar(Request $request, string $id): Response
    {
        $Enquete = new EnqueteEntity();
        $Enquete->uuid($id);
        $Enquete->set(lista: $request->dado());
        $Enquete->salvar();
        return new Response(status: 204);
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        $Enquete = new EnqueteEntity();
        $Enquete->uuid($id);
        $Enquete->destruir();
        return new Response(status: 204);
    }
}
