<?php

namespace App\Controllers\Api;

use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Models\Api\ParceiroLoja\LojaModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;

class ParceiroClubeController extends Controller implements
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
        $LojaEntity = new LojaEntity();
        $LojaEntity->id($id);
        return $this->retornoPadrao($LojaEntity);
    }

    /**
     * @param LojaEntity $lojaEntity
     * @param int        $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(LojaEntity $lojaEntity, int $status = 200): Response
    {
        return mensagemSucesso(pegarPropriedadeDaEntity($lojaEntity, lista: [
            'nome_fantasia', 'razao_social', 'titulo_interno', 'tipo_loja',
            'tipo_estabelecimento', 'endereco_estado', 'desconto', 'status'
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
        $LojaModel = new LojaModel();
        $LojaModel->set(lista: $request->dado());
        return mensagemSucesso($LojaModel->listarDados());
    }
}
