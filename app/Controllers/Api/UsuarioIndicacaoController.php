<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Modules\Email;
use Modules\Telefone;
use Controller\Controller;
use App\Classes\UsuarioIndicacao\Status;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Controllers\Api\Interface\BuscarInterface;
use App\Controllers\Api\Interface\ListarInterface;
use App\Controllers\Api\Interface\SalvarInterface;
use App\Controllers\Api\Interface\DeletarInterface;
use App\Models\Api\UsuarioIndicacao\IndicacaoModel;
use App\Models\Api\UsuarioIndicacao\IndicacaoEntity;
use App\Controllers\Api\Interface\AtualizarInterface;

final class UsuarioIndicacaoController extends Controller implements
    SalvarInterface,
    ListarInterface,
    BuscarInterface,
    AtualizarInterface,
    DeletarInterface
{

    public function postSalvar(Request $request)
    {
        try {
            $Cliente = new ClienteEntity();
            $Cliente->id($request->usuario);
        } catch (\Throwable $th) {
            mensagemErro('Erro!', 'Usuário enviado não foi encontrado');
        }

        $Indicacao = new IndicacaoEntity();
        $Indicacao->id_usuario_cliente = $Cliente->get('id');
        $Indicacao->nome = $request->nome;
        $Indicacao->email = new Email($request->email);
        $Indicacao->telefone = new Telefone($request->telefone);
        $Indicacao->salvar();

        return mensagemSucesso(['id' => $Indicacao->id] + $request->dado(), 201);
    }

    public function getListar(Request $request)
    {
        $Indicacao = new IndicacaoModel($request);
        $dado = $Indicacao->listar();

        return new Response(json: [
            'status' => 'sucesso',
            'dado' => $dado
        ]);
    }

    public function getBuscar(string $id)
    {
        if (empty($id)) {
            mensagemStatus(404);
        }

        $Indicacao = new IndicacaoEntity();
        $Indicacao->id($id);

        return mensagemSucesso([
            'id' => $Indicacao->id,
            'nome' => $Indicacao->nome,
            'email' => $Indicacao->email->email(),
            'telefone' => $Indicacao->telefone->numero(),
            'quem_indicou' => $Indicacao->quem_indicou,
            'usuario' => $Indicacao->usuario,
            'data_criacao' => $Indicacao->data_criacao->date(),
            'data_atualizacao' => $Indicacao->data_atualizacao->date(),
            'status' => $Indicacao->status->indice()
        ]);
    }

    public function putAtualizar(Request $request, string $id)
    {
        $Indicacao = new IndicacaoEntity();
        $Indicacao->id($id);
        $Indicacao->status = new Status($request->status);
        $Indicacao->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id)
    {
        $Indicacao = new IndicacaoEntity();
        $Indicacao->id($id);
        $Indicacao->destruir();

        return new Response(status: 204);
    }
}
