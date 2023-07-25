<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\ParceiroLoja\Helper;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\Automovel\Montadora\MontadoraModel;
use App\Models\Api\Automovel\Montadora\MontadoraEntity;

final class AutomovelMontadoraController extends Controller implements
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

        $Montadora = new MontadoraEntity();
        $Montadora->buscar([
            ['uuid', $id],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ]);

        return $this->retornoSucesso($Montadora);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Montadora = new MontadoraModel($request);

        $dado = $Montadora->listarDados();

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
        $Montadora = new MontadoraEntity($request);
        $Montadora->set(lista: $request->dado());
        $Montadora->salvar();

        return $this->retornoSucesso($Montadora, 201);
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

        $Montadora = new MontadoraEntity($request);
        $Montadora->buscar([
            ['uuid', $id],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ]);

        $Montadora->set(lista: $request->dado());
        $Montadora->salvar();

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

        $Montadora = new MontadoraEntity();
        $Montadora->id($id);
        $Montadora->destruir();

        return new Response(status: 204);
    }

    private function retornoSucesso(MontadoraEntity $Montadora, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                $Montadora,
                lista: [
                    'id', 'uuid', 'cod_parceiro', 'documento', 'tipo', 'titulo', 'bg', 'bg_banner', 'link_concessionaria', 'procedimento',
                    'empresa', 'ordem', 'status'
                ]
            ),
            status: $status,
        );
    }
}
