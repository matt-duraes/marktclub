<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use App\Models\Api\DownloadPrivado\ArquivoEntity;
use App\Models\Api\SolicitacaoVoucher\VoucherModel;
use App\Models\Api\SolicitacaoVoucher\DownloadModel;
use App\Models\Api\SolicitacaoVoucher\VoucherEntity;

final class SolicitacaoVoucherController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface
{
    public function getListar(Request $request): Response
    {
        $Voucher = new VoucherModel($request);
        $dado = $Voucher->listarDados();

        if (existeErro($dado, 'lista')) {
            mensagemErro(
                $dado->erro->titulo ?? 'Erro!',
                $dado->erro->mensagem ?? 'Ocorreu um erro ao listar os vouchers.',
            );
        }

        return mensagemSucesso($dado);
    }

    public function postDownload(Request $request)
    {
        $Voucher = new DownloadModel($request);
        $dado = $Voucher->download();

        $Download = new ArquivoEntity(
            $dado,
            $request->usuario
        );
        $Download->salvar();

        return mensagemSucesso([
            'id' => $Download->id
        ], status: 201);
    }

    public function getBuscar(string $id): Response
    {
        validarUuid($id);

        $Voucher = new VoucherEntity;
        $Voucher->id($id);

        return mensagemSucesso($Voucher->retorno());
    }
}
