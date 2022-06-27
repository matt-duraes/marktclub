<?php

namespace App\Controllers\Api;

use Http\Request;
use Modules\Data;
use Http\Response;
use Modules\Dinheiro;
use Controller\Controller;
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
        $Pagamento = new PagamentoModel(request: $request);
        $dado = $Pagamento->listarDados();

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
        $Usuario = new ClienteEntity();
        $Usuario->buscar(['cod', $request->usuario], false);
        if (empty($Usuario->id)) {
            mensagemErro('Usuário inválido!', 'Não foi encontrado um usuário para salvar esse pagamento.', 404);
        }

        $Pagamento = new PagamentoEntity(
            data_cobranca: new Data($request->data),
            valor_debito: new Dinheiro($request->valor),
            Usuario: $Usuario
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
