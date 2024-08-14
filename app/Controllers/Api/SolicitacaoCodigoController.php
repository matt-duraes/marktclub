<?php

namespace App\Controllers\Api;

use App\Classes\SolicitacaoCodigo\Ordem;
use App\Classes\SolicitacaoCodigo\Status;
use App\Models\Api\SolicitacaoVoucher\CodigoEntity;
use App\Models\Api\SolicitacaoVoucher\CodigoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Data;
use Modules\DataHora;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;

class SolicitacaoCodigoController extends Controller implements
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
        $CodigoEntity = new CodigoEntity();
        $CodigoEntity->uuid($id);
        return $this->retornoSucesso($CodigoEntity);
    }

    /**
     * @param CodigoEntity $codigoEntity
     * @param int          $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(CodigoEntity $codigoEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($codigoEntity, lista: [
                'id_admin_empresa', 'id_usuario_cliente', 'id_parceiro_loja',
                'codigo', 'data_emissao', 'data_vencimento', 'status',
                'data_criacao', 'data_atualizacao'
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
        $CodigoModel = new CodigoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->empresa,
            $request->usuario,
            $request->parceiro,
            new DataHora($request->data_emissao),
            new Data($request->data_vencimento),
            new Status($request->status)
        );
        return mensagemSucesso($CodigoModel->listarDados());
    }
}
