<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\Geral\Status;
use App\Classes\Automovel\Modelo\Ordem;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use App\Models\Api\Automovel\Modelo\ModeloModel;
use App\Models\Api\Automovel\Modelo\ModeloEntity;

final class AutomovelModeloController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface
{
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $Modelo = new ModeloEntity();
        $Modelo->idSlug($id);

        return $this->retornoSucesso($Modelo);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Modelo = new ModeloModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            parceiro: $request->parceiro,
            status: new Status($request->status),
            ordem: new Ordem($request->ordem),
        );
        return mensagemSucesso($Modelo->listarDados());
    }

    // /**
    //  * @param Request $request
    //  *
    //  * @return Response
    //  * @throws Excecao
    //  */
    // public function postSalvar(Request $request): Response
    // {
    //     $Modelo = new ModeloEntity($request);
    //     $Modelo->set(lista: $request->dado());
    //     $Modelo->salvar();

    //     return $this->retornoSucesso($Modelo, 201);
    // }

    // /**
    //  * @param Request $request
    //  *
    //  * @return Response
    //  * @throws Excecao
    //  */
    // public function putAtualizar(Request $request, string $id): Response
    // {
    //     validarUuid($id);

    //     $Modelo = new ModeloEntity($request);
    //     $Modelo->buscar([
    //         ['uuid', $id],
    //         ['status', 'in', Helper::STATUS_LIBERADO]
    //     ]);

    //     $Modelo->set(lista: $request->dado());
    //     $Modelo->salvar();

    //     return new Response(status: 204);
    // }

    // /**
    //  * @param Request $request
    //  *
    //  * @return Response
    //  * @throws Excecao
    //  */
    // public function deleteDeletar(string $id): Response
    // {
    //     validarUuid($id);

    //     $Modelo = new ModeloEntity();
    //     $Modelo->id($id);
    //     $Modelo->destruir();

    //     return new Response(status: 204);
    // }

    private function retornoSucesso(ModeloEntity $Modelo, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                $Modelo,
                lista: [
                    'titulo', 'link_logo', 'versao', 'url', 'status'
                ]
            ),
            status: $status,
        );
    }
}
