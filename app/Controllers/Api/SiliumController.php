<?php

namespace App\Controllers\Api;

use App\Models\Api\Silium\SiliumComissaoModel;
use App\Models\Api\Silium\SiliumDepositoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;

final class SiliumController extends Controller
{
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getSaldo(): Response
    {
        $SiliumComissaoModel = new SiliumComissaoModel();
        return mensagemSucesso([
            'saldo' => $SiliumComissaoModel->pegarSaldo()
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getExtrato(Request $request): Response
    {
        $SiliumDepositoModel = new SiliumDepositoModel($request);
        return mensagemSucesso($SiliumDepositoModel->gerarExtrato());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSaque(Request $request): Response
    {
        $SiliumDepositoModel = new SiliumDepositoModel($request);
        return mensagemSucesso($SiliumDepositoModel->realizarSaque(), 201);
    }
}
