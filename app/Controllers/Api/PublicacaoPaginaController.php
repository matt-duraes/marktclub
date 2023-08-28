<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\Geral\Status;
use App\Classes\PublicacaoPagina\Ordem;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\PublicacaoPagina\PaginaModel;
use App\Models\Api\PublicacaoPagina\PaginaEntity;
use System\Interface\ControllerAtualizarInterface;

final class PublicacaoPaginaController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface
{
    public function getListar(Request $request): Response
    {
        $Pagina = new PaginaModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            pesquisa: $request->pesquisa,
            ordem: new Ordem($request->ordem),
            status: new Status($request->status)
        );

        return mensagemSucesso($Pagina->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Pagina = new PaginaEntity();
        $Pagina->idSlug($id);

        return $this->retornoSucesso($Pagina);
    }

    public function postSalvar(Request $request): Response
    {
        $dado = $request->dado();
        $dado['texto'] = $request->getPost('texto', html: false);
        $Pagina = new PaginaEntity();
        $Pagina->set(lista: $dado);
        $Pagina->salvar();

        return $this->retornoSucesso($Pagina, 201);
    }

    private function retornoSucesso(PaginaEntity $Pagina, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                $Pagina,
                lista: [
                    'titulo', 'texto', 'data_criacao', 'data_atualizacao', 'header_titulo',
                    'header_descricao', 'header_tag', 'url', 'status'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $dado = $request->dado();
        $dado['texto'] = $request->getPut('texto', html: false);

        $Pagina = new PaginaEntity();
        $Pagina->idSlug($id);
        $Pagina->set(lista: $dado);
        $Pagina->salvar();

        return new Response(status: 204);
    }
}
