<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Modules\Pagina;
use Controller\Controller;
use App\Classes\Geral\Status;
use App\Classes\Automovel\Versao\Ordem;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\Automovel\Versao\VersaoModel;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\Automovel\Versao\VersaoEntity;
use System\Interface\ControllerAtualizarInterface;

final class AutomovelVersaoController extends Controller implements
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerDeletarInterface,
    ControllerAtualizarInterface,
    ControllerBuscarInterface
{
    public function getListar(Request $request): Response
    {
        $Versao = new VersaoModel(
            pagina: new Pagina($request->pagina),
            modelo: $request->modelo,
            status: new Status($request->status),
            ordem: new Ordem($request->ordem)
        );
        $dado = $Versao->listarDados();

        return mensagemSucesso($dado);
    }

    public function getBuscar(string $id): Response
    {
        $Versao = new VersaoEntity();
        $Versao->uuid($id);

        return $this->retornoSucesso($Versao, 200);
    }

    public function postSalvar(Request $request): Response
    {
        $Versao = new VersaoEntity();
        $Versao->set(lista: $request->dado());
        $Versao->salvar();

        return $this->retornoSucesso($Versao, 201);
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Versao = new VersaoEntity();
        $Versao->uuid($id);
        $Versao->set(lista: $request->dado());
        $Versao->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Versao = new VersaoEntity();
        $Versao->uuid($id);
        $Versao->destruir();

        return new Response(status: 204);
    }

    private function retornoSucesso(VersaoEntity $Versao, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                $Versao,
                lista: [
                    'titulo', 'cor', 'valor_de', 'valor_por', 'status'
                ]
            ),
            status: $status,
        );
    }
}
