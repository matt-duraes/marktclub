<?php

namespace App\Controllers\Api\Votacao;

use App\Models\Api\OrdenarModel;
use App\Models\Api\Votacao\Resposta\RespostaEntity;
use App\Models\Api\Votacao\Resposta\RespostaModel;
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
use System\Interface\ControllerOrdenarInterface;
use System\Interface\ControllerSalvarInterface;

final class RespostaController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface,
    ControllerOrdenarInterface
{
    /**
     * @param string $id
     *
     * @return Response
     */
    public function getBuscar(string $id): Response
    {
        $RespostaEntity = new RespostaEntity();
        $RespostaEntity->uuid($id);
        return $this->retornoPadrao($RespostaEntity);
    }

    /**
     * @param RespostaEntity $respostaEntity
     * @param int            $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(RespostaEntity $respostaEntity, int $status = 200): Response
    {
        return mensagemSucesso(pegarPropriedadeDaEntity($respostaEntity, lista: [
            'titulo', 'texto', 'voto_nulo', 'escrever_voto'
        ]), $status);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $RespostaModel = new RespostaModel();
        $RespostaModel->set(lista: $request->dado());
        return mensagemSucesso($RespostaModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $RespostaEntity = new RespostaEntity();
        $RespostaEntity->set(lista: $request->dado());
        $RespostaEntity->salvar();
        return $this->retornoPadrao($RespostaEntity, 201);
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
        $RespostaEntity = new RespostaEntity();
        $RespostaEntity->uuid($id);
        $RespostaEntity->set(lista: $request->dado());
        $RespostaEntity->salvar();
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
        $RespostaEntity = new RespostaEntity();
        $RespostaEntity->uuid($id);
        $RespostaEntity->destruir();
        return new Response(status: 204);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function putOrdenar(Request $request): Response
    {
        new OrdenarModel(
            id: jsonDecode($request->id, true, true),
            tabela: TABELA_VOTACAO_RESPOSTA,
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade)
        );
        return new Response(status: 204);
    }
}
