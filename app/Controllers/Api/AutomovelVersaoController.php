<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\Automovel\Versao\VersaoModel;
use App\Models\Api\Automovel\Versao\VersaoEntity;

final class AutomovelVersaoController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerDeletarInterface,
    ControllerAtualizarInterface
{
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        validarUuid($id);

        $Versao = new VersaoEntity();
        $Versao->buscar([
            ['uuid', $id],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ]);

        return $this->retornoSucesso($Versao);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Versao = new VersaoModel($request);

        $dado = $Versao->listarDados();

        return mensagemSucesso($dado);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Versao = new VersaoEntity($request);
        $Versao->set(lista: $request->dado());
        $Versao->salvar();

        return $this->retornoSucesso($Versao, 201);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function putAtualizar(Request $request, string $id): Response
    {
        validarUuid($id);

        $Versao = new VersaoEntity($request);
        $Versao->buscar([
            ['uuid', $id],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ]);

        $Versao->set(lista: $request->dado());
        $Versao->salvar();

        return new Response(status: 204);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        validarUuid($id);

        $Versao = new VersaoEntity();
        $Versao->id($id);
        $Versao->destruir();

        return new Response(status: 204);
    }

    private function retornoSucesso(VersaoEntity $Versao, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                $Versao,
                lista: [
                    'titulo', 'vinculo', 'detalhe', 'valor', 'valor_off', 'tipo', 'status'
                ]
            ),
            status: $status,
        );
    }
}
