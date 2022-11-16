<?php

namespace App\Controllers\Api;

use Http\Request;
use Modules\Data;
use Http\Response;
use Modules\Dinheiro;
use Controller\Controller;
use App\Classes\UsuarioPagamento\Helper;
use App\Classes\UsuarioPagamento\Status;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Controllers\Api\Interface\BuscarInterface;
use App\Controllers\Api\Interface\ListarInterface;
use App\Controllers\Api\Interface\SalvarInterface;
use App\Models\Api\UsuarioPagamento\PagamentoModel;
use App\Models\Api\UsuarioPagamento\PagamentoEntity;
use App\Controllers\Api\Interface\AtualizarInterface;

final class UsuarioPagamentoController extends Controller implements
    ListarInterface,
    BuscarInterface,
    SalvarInterface,
    AtualizarInterface
{
    public function getListar(Request $request)
    {

        $Pagamento = new PagamentoModel($request);
        $dado = $Pagamento->listarDados();
        $dado->lista = criptografarDado($dado->lista, Helper::CRIPTOGRAFIA);

        return mensagemSucesso($dado);
    }

    public function getBuscar(string $id)
    {
        $Pagamento = new PagamentoModel();
        $dado = $Pagamento->buscarPagamento($id);

        return mensagemSucesso($dado);
    }

    public function postSalvar(Request $request)
    {
        $Pagamento = new PagamentoEntity(
            data_cobranca: new Data($request->data),
            valor_debito: new Dinheiro($request->valor),
            usuario: $request->usuario
        );
        $Pagamento->salvar();

        return mensagemSucesso([
            'id' => $Pagamento->id,
            'valor' => $Pagamento->valor_debito->decimal(),
            'data' => $Pagamento->data_cobranca->date()
        ], status: 201);
    }

    public function putAtualizar(Request $request, string $id)
    {
        $Pagamento = new PagamentoEntity();
        $Pagamento->id($id);
        $Pagamento->status = new Status($request->status);
        $Pagamento->salvar();

        return new Response(status: 204);
    }
}
