<?php

namespace ApiController;

use Http\Request;
use Http\Response;
use Controller\Controller;
use ApiModel\Log\ErrorModel;
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
    public function postSalvar(Request $request): Response
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
        } catch (\Throwable $e) {
            if ($e->getMessage() != 'erro_duplicado') {
                mensagemStatus(404, error: $e, localhost: 'Não foi possível salvar o log.');
            }
        }

        return mensagemSucesso([
            'id' => $Error->id
        ], status: 201);
    }
    public function getBuscar(string $id): Response
    {
        $Error = new ErrorEntity();
        $Error->uuid($id);

        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Error,
                lista: [
                    'id', 'mensagem', 'codigo', 'arquivo', 'linha', 'trace', 'quantidade',
                    'status_http', 'data_criacao', 'status'
                ]
            ),
        );
    }
    public function getListar(Request $request): Response
    {
        $Error = new ErrorModel($request);
        $dado = $Error->listarDado();

        return mensagemSucesso($dado);
    }
    public function putAtualizar(Request $request, string $id): Response
    {
        $Error = new ErrorEntity();
        $Error->uuid($id);
        $Error->status = new Status($request->status);
        $Error->salvar();

        return new Response(status: 204);
    }
}
