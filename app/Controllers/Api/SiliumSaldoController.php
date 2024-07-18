<?php

namespace App\Controllers\Api;

use App\Classes\SiliumSaldo\Ordem;
use App\Models\Api\SiliumSaldo\SiliumSaldoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerListarInterface;

final class SiliumSaldoController extends Controller implements
    ControllerListarInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $SiliumSaldoModel = new SiliumSaldoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->usuario,
            new Data($request->data_inicio),
            new Data($request->data_final)
        );
        return mensagemSucesso($SiliumSaldoModel->listarDados());
    }
}
