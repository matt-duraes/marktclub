<?php

namespace App\Controllers\Api;

use App\Models\Api\Popup\PopupEntity;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\DataHora;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerSalvarInterface;

class PopupController extends Controller implements
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    /**
     * @param string $id Identificação(Uuid) do Pop-up
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $PopupEntity = new PopupEntity();
        $PopupEntity->uuid(
            $id,
            mensagem: 'Não foi possível realizar está ação',
            titulo: 'Código inválido ou inexistente'
        );
        return $this->retornoPadrao($PopupEntity);
    }

    /**
     * @param PopupEntity $PopupEntity Entidade do Popup
     * @param int         $status      Status code que deverá ser retornado
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(PopupEntity $PopupEntity, int $status = 200): Response
    {
        $dados = pegarPropriedadeDaEntity(
            $PopupEntity,
            lista: [
                'slug', 'titulo', 'subtitulo', 'texto', 'formulario', 'imagem',
                'data_criacao', 'data_expiracao', 'status'
            ]
        );
        $dados['formulario'] = jsonDecode($dados['formulario']);
        $dados['data_criacao'] = (new DataHora($dados['data_criacao']))->data();
        $dados['data_expiracao'] = (new DataHora($dados['data_expiracao']))->data();
        return mensagemSucesso($dados, $status);
    }

    /**
     * @param Request $request Requisição
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $PopupEntity = new PopupEntity();
        $PopupEntity->set(lista: $request->dado());
        $PopupEntity->salvar();
        return $this->retornoPadrao($PopupEntity, 201);
    }

    /**
     * @param Request $request Requisição
     * @param string  $id      Identificação(Uuid) do Pop-up
     *
     * @return Response
     * @throws Excecao
     */
    public function putAtualizar(Request $request, string $id): Response
    {
        $PopupEntity = new PopupEntity();
        $PopupEntity->uuid(
            $id,
            mensagem: 'Não foi possível realizar está ação',
            titulo: 'Código inválido ou inexistente'
        );
        $PopupEntity->set(lista: $request->dado());
        $PopupEntity->salvar();
        return $this->retornoPadrao($PopupEntity);
    }

    /**
     * @param string $id Identificação(Uuid) do Pop-up
     *
     * @return Response
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        $PopupEntity = new PopupEntity();
        $PopupEntity->uuid($id);
        $PopupEntity->destruir();
        return new Response(status: 204);
    }
}
