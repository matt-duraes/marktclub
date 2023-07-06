<?php

namespace App\Controllers\Api;

use Http\Request;
use Modules\Data;
use Http\Response;
use Modules\Dinheiro;
use Controller\Controller;
use App\Classes\UsuarioPagamento\Helper;
use App\Classes\UsuarioPagamento\Status;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\UsuarioPagamento\PagamentoModel;
use App\Models\Api\UsuarioPagamento\PagamentoEntity;

final class UsuarioPagamentoController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface
{
    public function getListar(Request $request): Response
    {
        $Pagamento = new PagamentoModel($request);
        $dado = $Pagamento->listarDados();

        $dado->lista = criptografarDado(
            dado: $dado->lista,
            criptografia: Helper::CRIPTOGRAFIA,
            lista: true
        );

        return mensagemSucesso($dado);
    }

    public function getBuscar(string $id): Response
    {
        $Pagamento = new PagamentoModel();
        $dado = $Pagamento->buscarPagamento($id);

        return mensagemSucesso($dado);
    }

    public function postSalvar(Request $request): Response
    {
        $Pagamento = new PagamentoEntity(
            data_cobranca: new Data($request->data),
            valor_debito: new Dinheiro($request->valor),
            usuario: $request->usuario
        );
        $Pagamento->salvar();

        return mensagemSucesso([
            'id'    => $Pagamento->id,
            'valor' => $Pagamento->valor_debito->decimal(),
            'data'  => $Pagamento->data_cobranca->date()
        ], status: 201);
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Pagamento = new PagamentoEntity();
        $Pagamento->uuid($id);
        $Pagamento->status = new Status($request->status);
        $Pagamento->salvar();

        return new Response(status: 204);
    }
}
