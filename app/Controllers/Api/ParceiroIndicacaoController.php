<?php

namespace App\Controllers\Api;

use App\Classes\ParceiroIndicacao\Ordem;
use App\Classes\ParceiroIndicacao\Status;
use App\Models\Api\ParceiroIndicacao\ParceiroIndicacaoEntity;
use App\Models\Api\ParceiroIndicacao\ParceiroIndicacaoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class ParceiroIndicacaoController extends Controller implements
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Parceiro = new ParceiroIndicacaoModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            ordem: new Ordem($request->ordem),
            status: new Status($request->status)
        );
        return mensagemSucesso($Parceiro->listarDados());
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $Parceiro = new ParceiroIndicacaoEntity();
        $Parceiro->uuid($id);
        return $this->retornoSucesso($Parceiro);
    }

    /**
     * @param ParceiroIndicacaoEntity $parceiroIndicacaoEntity
     * @param int                     $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(ParceiroIndicacaoEntity $parceiroIndicacaoEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($parceiroIndicacaoEntity, lista: [
                'nome', 'email', 'telefone', 'mensagem', 'status'
            ]),
            $status
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Parceiro = new ParceiroIndicacaoEntity();

        $Parceiro->set(lista: $request->dado());
        $Parceiro->salvar();
        return $this->retornoSucesso($Parceiro, 201);
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return Response
     * @throws Excecao
     */
    public function putAtualizar(Request $request, string $id): Response
    {
        $Parceiro = new ParceiroIndicacaoEntity();

        $Parceiro->uuid($id);
        $Parceiro->set(lista: $request->dado());
        $Parceiro->salvar();

        return new Response(status: 204);
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        $Parceiro = new ParceiroIndicacaoEntity();

        $Parceiro->uuid($id);
        $Parceiro->destruir();

        return new Response(status: 204);
    }
}
