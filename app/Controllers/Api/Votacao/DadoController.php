<?php

namespace App\Controllers\Api\Votacao;

use App\Models\Api\Votacao\Dado\CancelarModel;
use App\Models\Api\Votacao\Dado\DadoEntity;
use App\Models\Api\Votacao\Dado\DadoModel;
use App\Models\Api\Votacao\Resultado\RetornoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Botao;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

final class DadoController extends Controller implements
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
        $VotacaoEntity = new DadoEntity();
        $VotacaoEntity->idSlug($id);
        return $this->retornoPadrao($VotacaoEntity);
    }

    /**
     * @param DadoEntity $votacaoEntity
     * @param int        $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(DadoEntity $votacaoEntity, int $status = 200): Response
    {
        return mensagemSucesso(pegarPropriedadeDaEntity($votacaoEntity, lista: [
            'titulo', 'texto', 'tipo', 'voto_unico', 'data_inicio', 'data_final',
            'publicado', 'bloqueado', 'status_votacao', 'identificar_usuario', 'status'
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
        $VotacaoModel = new DadoModel();
        $VotacaoModel->set(lista: $request->dado());
        return mensagemSucesso($VotacaoModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $dado = $request->dado();
        if (!$request->vazio('texto')) {
            $dado['texto'] = $request->getPost('texto', html: false);
        }
        $VotacaoEntity = new DadoEntity();
        $VotacaoEntity->set(lista: $dado);
        $VotacaoEntity->salvar();
        return $this->retornoPadrao($VotacaoEntity, 201);
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
        $dado = $request->dado();
        if (!$request->vazio('texto')) {
            $dado['texto'] = $request->getPut('texto', html: false);
        }
        $Votacao = new DadoEntity();
        $Votacao->uuid($id);
        $Votacao->set(lista: $dado);
        $Votacao->salvar();
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
        $VotacaoEntity = new DadoEntity();
        $VotacaoEntity->uuid($id);
        $VotacaoEntity->destruir();
        return new Response(status: 204);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postCancelar(Request $request): Response
    {
        new CancelarModel($request->id);
        return mensagemSucesso([
            'id' => $request->id
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postBloquear(Request $request): Response
    {
        $VotacaoEntity = new DadoEntity();
        $VotacaoEntity->uuid($request->id);
        $VotacaoEntity->bloqueado = new Botao(Botao::SIM);
        $VotacaoEntity->salvar();
        return mensagemSucesso([
            'id' => $VotacaoEntity->id
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postResultado(Request $request): Response
    {
        $Resultado = new RetornoModel($request->id);
        return mensagemSucesso($Resultado->retorno);
    }
}
