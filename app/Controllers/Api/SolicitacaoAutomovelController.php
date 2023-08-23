<?php

namespace App\Controllers\Api;

use Http\Request;
use Modules\Data;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\Solicitacao\Status;
use App\Classes\SolicitacaoAutomovel\Ordem;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\SolicitacaoAutomovel\AutomovelModel;
use App\Models\Api\SolicitacaoAutomovel\AutomovelEntity;

class SolicitacaoAutomovelController extends Controller implements
    ControllerSalvarInterface,
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface
{
    public function getListar(Request $request): Response
    {
        $Automovel = new AutomovelModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            dataCriacaoDe: new Data($request->data_criacao_de),
            dataCriacaoAte: new Data($request->data_criacao_ate),
            status: new Status($request->status),
            empresa: $request->empresa,
            ordem: new Ordem($request->ordem)
        );
        return mensagemSucesso($Automovel->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Automovel = new AutomovelEntity();
        $Automovel->uuid($id);

        return $this->retornoSucesso($Automovel);
    }

    public function postSalvar(Request $request): Response
    {
        $Automovel = new AutomovelEntity();
        $Automovel->set(lista: $request->dado());
        $Automovel->salvar();

        return $this->retornoSucesso($Automovel, 201);
    }

    private function retornoSucesso(AutomovelEntity $Automovel, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Automovel,
                lista: [
                    'endereco_estado', 'endereco_cidade', 'montadora', 'modelo', 'versao', 'cor',
                    'data_criacao', 'data_atualizacao', 'mensagem', 'status'
                ]
            ),
            $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Automovel = new AutomovelEntity();
        $Automovel->uuid($id);
        $Automovel->set(lista: $request->dado());
        $Automovel->salvar();

        return new Response(status: 204);
    }
}
