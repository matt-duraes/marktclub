<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\Log\ErrorEntity;

final class LogController extends Controller
{
    public function postError(Request $request)
    {
        try {
            $Error = new ErrorEntity(
                $request->mensagem,
                $request->codigo,
                $request->status,
                $request->arquivo,
                $request->linha,
                $request->trace,
            );
            $Error->salvar();
        } catch (\Throwable $erro) {
            if ($erro->getMessage() != 'erro_duplicado') {
                mensagemStatus(404);
            }
        }

        return new Response(json: [
            'id' => $Error->hash
        ], status: 201);
    }
}
