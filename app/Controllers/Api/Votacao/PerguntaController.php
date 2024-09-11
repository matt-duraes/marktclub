<?php

namespace App\Controllers\Api\Votacao;

use App\Models\Api\OrdenarModel;
use App\Models\Api\Votacao\Pergunta\PerguntaEntity;
use App\Models\Api\Votacao\Pergunta\PerguntaModel;
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

final class PerguntaController extends Controller implements
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
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $PerguntaEntity = new PerguntaEntity();
        $PerguntaEntity->uuid($id);
        return $this->retornoPadrao($PerguntaEntity);
    }

    /**
     * @param PerguntaEntity $perguntaEntity
     * @param int            $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(PerguntaEntity $perguntaEntity, int $status = 200): Response
    {
        return mensagemSucesso(pegarPropriedadeDaEntity($perguntaEntity, lista: [
            'titulo', 'texto', 'tipo', 'pode_nulo'
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
        $PerguntaModel = new PerguntaModel();
        $PerguntaModel->set(lista: $request->dado());
        return mensagemSucesso($PerguntaModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $PerguntaEntity = new PerguntaEntity();
        $PerguntaEntity->set(lista: $request->dado());
        $PerguntaEntity->salvar();
        return $this->retornoPadrao($PerguntaEntity, 201);
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
        $PerguntaEntity = new PerguntaEntity();
        $PerguntaEntity->uuid($id);
        $PerguntaEntity->set(lista: $request->dado());
        $PerguntaEntity->salvar();
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
        $PerguntaEntity = new PerguntaEntity();
        $PerguntaEntity->uuid($id);
        $PerguntaEntity->destruir();
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
            tabela: TABELA_VOTACAO_PERGUNTA,
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade)
        );
        return new Response(status: 204);
    }
}
