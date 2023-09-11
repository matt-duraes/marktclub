<?php

namespace App\Controllers\Api;

use App\Classes\ChatbotPerguntas\Ordem;
use App\Classes\Geral\Status;
use App\Models\Api\ChatbotPerguntas\PerguntasEntity;
use App\Models\Api\ChatbotPerguntas\PerguntasModel;
use Controller\Controller;
use Helpers\OrmHelper;
use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class ChatbotPerguntasController extends Controller implements
    ControllerSalvarInterface, ControllerBuscarInterface,
    ControllerListarInterface, ControllerAtualizarInterface
{
    public function getListar(Request $request): Response
    {
        $Perguntas = new PerguntasModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            status: new Status($request->status),
            categoria: $request->categoria,
            ordem: new Ordem($request->ordem)
        );
        return mensagemSucesso($Perguntas->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Perguntas = new PerguntasEntity();
        $Perguntas->uuid($id);

        return $this->retornoPadrao($Perguntas);
    }

    public function postSalvar(Request $request): Response
    {
        $dado = $request->dado();
        $dado['resposta'] = $request->getPost('resposta', html: false);

        $Perguntas = new PerguntasEntity();
        $Perguntas->set(lista: $dado);
        $Perguntas->salvar();

        return $this->retornoPadrao($Perguntas, 201);
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $dado = $request->dado();

        if(!$request->vazio('resposta')) {
            $dado['resposta'] = $request->getPut('resposta', html: false);
        }

        $Perguntas = new PerguntasEntity();
        $Perguntas->uuid($id);
        $Perguntas->set(lista: $dado);
        $Perguntas->salvar();

        return new Response(status: 204);
    }

    public function postPerguntar(Request $request)
    {
        $Perguntas = new PerguntasEntity();
        $ormHelper = new OrmHelper(TABELA_CHATBOT_CATEGORIA);

        $idCategoria = $ormHelper
            ->pegarPrimeiroRegistro(
                where: ['categoria', $request->getPost('categoria')],
                campo: ['id']
            );

        $Perguntas->buscar(
            where: [
                ['pergunta', $request->getPost('pergunta')],
                ['categoria', $idCategoria['id']],
                ['status', (new Status(Status::ATIVO))->numero()]
            ],
            mensagem: "Pergunta não encontrada, tente novamente!",
            titulo: "Não encontrada"
        );

        return $this->retornoPadrao($Perguntas);
    }

    private function retornoPadrao(PerguntasEntity $Regra, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity($Regra, lista: [
                'categoria', 'pergunta', 'resposta', 'status'
            ]),
            status: $status
        );
    }
}
