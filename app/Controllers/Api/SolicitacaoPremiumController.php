<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerListarInterface;
use App\Models\Api\DownloadPrivado\ArquivoEntity;
use App\Models\Api\SolicitacaoPremium\PremiumModel;
use App\Models\Api\SolicitacaoPremium\DownloadModel;

final class SolicitacaoPremiumController extends Controller implements ControllerListarInterface
{
    public function getListar(Request $request): Response
    {
        $Premium = new PremiumModel($request);
        return mensagemSucesso($Premium->listarDados());
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
}
