<?php

namespace App\Controllers\Api;

use App\Classes\ParceiroCashback\Categoria;
use ORM\Entity;
use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\Geral\Status;
use App\Classes\ParceiroCashback\Ordem;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\ParceiroCashback\CashbackModel;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\ParceiroCashback\CashbackEntity;

final class ParceiroCashbackController extends Controller implements
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Cashback = new CashbackModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            empresa: $request->empresa,
            status: new Status($request->status),
            ordem: new Ordem($request->ordem),
            pesquisa: $request->pesquisa,
            categoria: new Categoria($request->categoria)
        );
        return mensagemSucesso($Cashback->pegarRetorno());
    }

    public function getBuscar(string $id): Response
    {
        $Cashback = new CashbackEntity();
        $Cashback->idSlug($id);

        return $this->retornoSucesso($Cashback);
    }

    public function postSalvar(Request $request): Response
    {
        $Cashback = new CashbackEntity();
        $Cashback->set(lista: $request->dado());
        $Cashback->salvar();

        return $this->retornoSucesso($Cashback, 201);
    }

    public function retornoSucesso(Entity $Entity, int $status = 200): Response
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Entity,
                lista: [
                    'titulo', 'texto_descricao', 'texto_restricao', 'texto_outro',
                    'imagem', 'logo', 'comissao', 'comissao_minima', 'comissao_maxima', 'link_site',
                    'link_usuario', 'empresa', 'url', 'status', 'categoria'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Cashback = new CashbackEntity();
        $Cashback->uuid($id);
        $Cashback->set(lista: $request->dado());
        $Cashback->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Cashback = new CashbackEntity();
        $Cashback->uuid($id);
        $Cashback->destruir();

        return new Response(status: 204);
    }
}
