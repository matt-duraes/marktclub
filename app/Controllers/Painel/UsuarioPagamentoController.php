<?php

namespace App\Controllers\Painel;

use Http\Request;
use Modules\Data;
use Http\Response;
use Modules\Dinheiro;
use Helpers\ApiHelper;
use Controller\Controller;

final class UsuarioPagamentoController extends Controller
{
    public function postSalvar(Request $request)
    {
        $data = new Data($request->data);
        $valor = new Dinheiro($request->valor);

        if ($data->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo data da cobrança é obrigatório.');
        } elseif (!$data->valido()) {
            mensagemErro('Campo inválido!', 'O campo data da cobrança não é um valor válido.');
        } elseif ($valor->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo valor é obrigatório.');
        } elseif (!$valor->valido()) {
            mensagemErro('Campo inválido!', 'O campo valor não é um valor válido.');
        } elseif (empty($request->usuario)) {
            mensagemErro('Erro!', 'Ocorreu um erro ao salvar, por favor, tente novamente.');
        }

        $Api = new ApiHelper(token: true);
        $pagamento = $Api->body([
            'data' => $data->date(),
            'valor' => $valor->decimal(),
            'usuario' => $request->usuario
        ])->post('/usuario-pagamento')->object();

        if (existeErro($pagamento, 'dado')) {
            mensagemErro(
                $pagamento->erro->titulo ?? 'Erro!',
                $pagamento->erro->mensagem ?? 'Ocorreu um erro ao salvar, por favor, tente novamente.'
            );
        }

        return mensagemSucesso([
            'id' => $pagamento->dado->id,
            'data' => $pagamento->dado->data,
            'valor' => $pagamento->dado->valor,
        ], status: 201);
    }

    public function deleteDeletar(string $id)
    {
        if (empty($id)) {
            mensagemErro('Erro!', 'Ocorreu um erro ao dar baixa, por favor, tente novamente.');
        }

        $Api = new ApiHelper(token: true);
        $pagamento = $Api->body([
            'status' => 'pago',
        ])->put('/usuario-pagamento/' . $id);

        if ($pagamento->status() != 204) {
            mensagemErro('Erro!', 'Ocorreu um erro ao dar baixa, por favor, tente novamente.');
        }

        return new Response(status: 204);
    }
}
