<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerListarInterface;
use App\Models\Api\SolicitacaoSalavip\SalavipModel;

final class SolicitacaoSalavipController extends Controller implements
    ControllerListarInterface
{
    public function getListar(Request $request): Response
    {
        $Voucher = new SalavipModel($request);
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
        $Voucher = new SalavipModel($request);
        $dado = $Voucher->download();

        return new Response(json: [
            'status' => 'sucesso',
            'dado' => $dado
        ], status: 201);
    }
}
