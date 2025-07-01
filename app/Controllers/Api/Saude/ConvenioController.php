<?php

namespace App\Controllers\Api\Saude;

use App\Models\Api\Saude\Convenio\BuscarModel;
use App\Models\Api\Saude\Convenio\CidadeModel;
use App\Models\Api\Saude\Convenio\EstadoModel;
use App\Models\Api\Saude\Convenio\ListarModel;
use App\Models\Api\Saude\Convenio\PlanoEntity;
use Controller\Controller;
use Http\Request;
use Http\Response;
use Modules\EnderecoEstado;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

final class ConvenioController extends Controller implements
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerDeletarInterface,
    ControllerAtualizarInterface,
    ControllerBuscarInterface
{
    public function getListar(Request $request): Response
    {
        $Convenio = new ListarModel(
            EnderecoEstado: new EnderecoEstado($request->endereco_estado),
            enderecoCidade: $request->endereco_cidade,
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade)
        );
        return mensagemSucesso($Convenio->retorno);
    }

    public function getBuscar(string $id): Response
    {
        $Buscar = new BuscarModel(id: $id);
        return mensagemSucesso($Buscar->retorno);
    }

    public function postSalvar(Request $request): Response
    {
        $Salvar = new PlanoEntity();
        $Salvar->set(lista: $request->dado());
        $Salvar->salvar();

        return mensagemSucesso(dado: [
            'id' => $Salvar->id,
        ], status: 201);
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Atualizar = new PlanoEntity();
        $Atualizar->uuid($id);
        $Atualizar->set(lista: $request->dado());
        $Atualizar->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Deletar = new PlanoEntity();
        $Deletar->uuid($id);
        $Deletar->destruir();

        return new Response(status: 204);
    }

    public function getEstado(): Response
    {
        $Estado = new EstadoModel();
        return mensagemSucesso($Estado->retorno);
    }

    public function getCidade(Request $request): Response
    {
        $Cidade = new CidadeModel(
            EnderecoEstado: new EnderecoEstado($request->endereco_estado),
        );
        return mensagemSucesso($Cidade->retorno);
    }
}
