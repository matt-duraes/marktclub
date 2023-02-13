<?php

namespace ApiController;

use Http\Request;
use Http\Response;
use Controller\Controller;
use ApiModel\Log\ErrorEntity;
use System\Classes\LogErro\Status;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerAtualizarInterface;

final class LogErroController extends Controller implements
    ControllerSalvarInterface,
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerAtualizarInterface
{
    public function postSalvar(Request $request)
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
    public function getBuscar(string $id)
    {
        $Error = new ErrorEntity();
        $Error->id($id);

        return mensagemSucesso(
            pegarPropriedadeDaEntity($Error)
        );
    }
    public function getListar(Request $request)
    {
        $Error = new ErrorModel($request);
        $dado = $Error->listarDado();

        return mensagemSucesso($dado);
    }
    public function putAtualizar(Request $request, string $id)
    {
        $Error = new ErrorEntity();
        $Error->id($id);
        $Error->status = new Status($request->status);
    }
}
