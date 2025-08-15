<?php

namespace App\Controllers\Api;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\CampanhaVoucher\Ordem;
use App\Classes\CampanhaVoucher\Status;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\CampanhaVoucher\CampanhaVoucherModel;
use App\Models\Api\CampanhaVoucher\CampanhaVoucherEntity;
use App\Models\Api\CampanhaVoucher\ValidarUsuarioTemVoucherModel;

class CampanhaVoucherController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
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
        $CampanhaVoucherEntity = new CampanhaVoucherEntity();
        $CampanhaVoucherEntity->uuid($id);
        return $this->retornoSucesso($CampanhaVoucherEntity);
    }

    /**
     * @param CampanhaVoucherEntity $campanhaVoucherEntity
     * @param int                   $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(CampanhaVoucherEntity $campanhaVoucherEntity, int $status = 200): Response
    {
        return mensagemSucesso(pegarPropriedadeDaEntity($campanhaVoucherEntity, lista: [
            'voucher', 'data_vencimento', 'status',
            'data_criacao', 'data_atualizacao'
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
        $CampanhaVoucherModel = new CampanhaVoucherModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            new Status($request->status)
        );
        return mensagemSucesso($CampanhaVoucherModel->listarDados());
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
        $CampanhaVoucherEntity = new CampanhaVoucherEntity();
        $CampanhaVoucherEntity->uuid($id, mensagem: 'Voucher não encontrado ou inexistente.');
        $CampanhaVoucherEntity->set(lista: $request->dado());
        $CampanhaVoucherEntity->salvar();
        return new Response(status: 204);
    }

    /**
     * @throws Excecao
     */
    public function getResgatar(): Response
    {
        $CampanhaVoucherEntity = new CampanhaVoucherEntity();
        return mensagemSucesso([
            'voucher' => $CampanhaVoucherEntity->resgatarVoucher()
        ]);
    }

    /**
     * @throws Excecao
     */
    public function getDisponivel(): Response
    {
        $Existe = new ValidarUsuarioTemVoucherModel();
        return mensagemSucesso([
            'temVoucher' => $Existe->existe ? Botao::SIM : Botao::NAO
        ]);
    }
}
