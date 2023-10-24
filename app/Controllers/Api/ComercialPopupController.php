<?php

namespace App\Controllers\Api;

use Erro\Excecao;
use Http\Request;
use Modules\Data;
use Http\Response;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Models\Api\OrdenarModel;
use App\Classes\ComercialPopup\Ordem;
use App\Classes\ComercialPopup\Status;
use App\Models\Api\ComercialPopup\PopupModel;
use App\Models\Api\ComercialPopup\PopupEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;
use System\Interface\ControllerAtualizarInterface;

class ComercialPopupController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
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
        $PopupEntity->uuid($id);
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
        return mensagemSucesso(
            pegarPropriedadeDaEntity($PopupEntity, lista: [
                'id', 'slug', 'imagem', 'titulo', 'texto', 'regulamento', 'data_inicio',
                'data_final', 'atualizar_dado', 'botao_texto', 'botao_link',
                'botao_target', 'status'
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
    public function getListar(Request $request): Response
    {
        $PopupModel = new PopupModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->titulo,
            $request->empresa,
            new Data($request->data_inicio),
            new Data($request->data_final),
            new Status($request->status),
            new Botao($request->publicado)
        );
        return mensagemSucesso($PopupModel->listarDados());
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
        $PopupEntity->uuid($id);
        $PopupEntity->set(lista: $request->dado());
        $PopupEntity->salvar();
        return new Response(status: 204);
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

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function putOrdenar(Request $request): Response
    {
        new OrdenarModel(
            jsonDecode($request->id, true, true),
            TABELA_COMERCIAL_POPUP,
            new Pagina($request->pagina),
            new Quantidade($request->quantidade)
        );
        return new Response(status: 204);
    }

    /**
     * @param string $id Identificação(Uuid) da Empresa
     *
     * @return Response
     * @throws Excecao
     */
    public function getPopup(string $id): Response
    {
        $EmpresaEntity = new EmpresaEntity();
        $EmpresaEntity->uuid($id);
        return mensagemSucesso((new PopupModel())->pegarPopupDoDia($EmpresaEntity));
    }
}
