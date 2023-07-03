<?php

namespace App\Controllers\Api;

use App\Models\Api\Siliium\SiliumComissaoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;

class SiliumController extends Controller
{
    /**
     * @param  Request  $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getSaldo(Request $request): Response
    {
        $SiliumComissaoModel = new SiliumComissaoModel($request);
        return mensagemSucesso([
            'saldo' => $SiliumComissaoModel->pegarSaldo($request->id ?? null)
        ]);
    }

    /**
     * @param  Request  $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getExtrato(Request $request): Response
    {
        $SiliumComissaoModel = new SiliumComissaoModel($request);
        return mensagemSucesso($SiliumComissaoModel->retirarExtrato());
    }
}
