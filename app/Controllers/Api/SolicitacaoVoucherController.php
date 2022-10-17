<?php

namespace App\Controllers\Api;

use Http\Request;
use Controller\Controller;
use App\Controllers\Api\Interface\BuscarInterface;
use App\Controllers\Api\Interface\ListarInterface;
use App\Models\Api\SolicitacaoVoucher\VoucherModel;
use App\Models\Api\SolicitacaoVoucher\VoucherEntity;

final class SolicitacaoVoucherController extends Controller implements
    BuscarInterface,
    ListarInterface
{
    public function getListar(Request $request)
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
    public function getBuscar(string $id)
    {
        validarUuid($id);

        $Voucher = new VoucherEntity;
        $Voucher->id($id);

        return mensagemSucesso($Voucher->retorno());
    }
}
