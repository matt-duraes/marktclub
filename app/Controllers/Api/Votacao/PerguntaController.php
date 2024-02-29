<?php

namespace App\Controllers\Api\Votacao;

use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Models\Api\OrdenarModel;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerOrdenarInterface;
use App\Models\Api\Votacao\Pergunta\PerguntaModel;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\Votacao\Pergunta\PerguntaEntity;

final class PerguntaController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface,
    ControllerOrdenarInterface
{
    public function getListar(Request $request): Response
    {
        $Votacao = new PerguntaModel();
        $Votacao->set(lista: $request->dado());

        return mensagemSucesso($Votacao->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Pergunta = new PerguntaEntity();
        $Pergunta->uuid($id);

        return $this->retornoPadrao(Pergunta: $Pergunta, status: 200);
    }

    public function postSalvar(Request $request): Response
    {
        $Pergunta = new PerguntaEntity();
        $Pergunta->set(lista: $request->dado());
        $Pergunta->salvar();

        return $this->retornoPadrao(Pergunta: $Pergunta, status: 201);
    }

    private function retornoPadrao(PerguntaEntity $Pergunta, int $status): Response
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Pergunta,
                lista: [
                    'titulo', 'texto', 'tipo', 'pode_nulo'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Pergunta = new PerguntaEntity();
        $Pergunta->uuid($id);
        $Pergunta->set(lista: $request->dado());
        $Pergunta->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Pergunta = new PerguntaEntity();
        $Pergunta->uuid($id);
        $Pergunta->destruir();

        return new Response(status: 204);
    }

    public function putOrdenar(Request $request): Response
    {
        new OrdenarModel(
            id: jsonDecode($request->id, true, true),
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            tabela: TABELA_VOTACAO_PERGUNTA
        );

        return new Response(status: 204);
    }
}
