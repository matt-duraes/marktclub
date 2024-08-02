<?php

namespace App\Controllers\Api\Parceiro;

use App\Models\Api\Parceiro\Externo\DownloadModel;
use App\Models\Api\Parceiro\Externo\ExternoEntity;
use App\Models\Api\Parceiro\Externo\ExternoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDownloadInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

final class ExternoController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerDownloadInterface
{
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $ExternoEntity = new ExternoEntity();
        $ExternoEntity->uuid($id);
        return $this->retornoPadrao($ExternoEntity);
    }

    /**
     * @param ExternoEntity $externoEntity
     * @param int           $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(ExternoEntity $externoEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($externoEntity, lista: [
                'titulo_interno', 'dono', 'categoria_principal',
                'data_criacao', 'status', 'tipo_indicador', 'contato'
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
        $ExternoModel = new ExternoModel();
        $ExternoModel->set(lista: $request->dado());
        return mensagemSucesso($ExternoModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $ExternoEntity = new ExternoEntity();
        $ExternoEntity->set(lista: $request->dado());
        $ExternoEntity->salvar();
        return $this->retornoPadrao($ExternoEntity, 201);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postDownload(Request $request): Response
    {
        $Download = new DownloadModel($request);
        return mensagemSucesso([
            'id' => $Download->id
        ], 201);
    }
}
