<?php

namespace App\Controllers\Api;

use Http\Request;
use Modules\Data;
use Http\Response;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\Geral\Status;
use App\Classes\PublicacaoNoticia\Ordem;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\PublicacaoNoticia\NoticiaModel;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\PublicacaoNoticia\NoticiaEntity;

final class PublicacaoNoticiaController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Noticia = new NoticiaModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            pesquisa: $request->pesquisa,
            data_inicio_de: new Data($request->data_inicio_de),
            data_inicio_ate: new Data($request->data_inicio_ate),
            publicado: new Botao($request->publicado),
            ordem: new Ordem($request->ordem),
            status: new Status($request->status)
        );

        return mensagemSucesso($Noticia->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Noticia = new NoticiaEntity();
        $Noticia->idSlug($id);

        return $this->retornoSucesso($Noticia);
    }

    public function postSalvar(Request $request): Response
    {
        $Noticia = new NoticiaEntity();
        $Noticia->set(lista: $request->dado());
        $Noticia->salvar();

        return $this->retornoSucesso($Noticia, 201);
    }

    private function retornoSucesso(NoticiaEntity $Noticia, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                $Noticia,
                lista: [

                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Noticia = new NoticiaEntity();
        $Noticia->idSlug($id);
        $Noticia->set(lista: $request->dado());
        $Noticia->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Noticia = new NoticiaEntity();
        $Noticia->uuid($id);
        $Noticia->destruir();

        return new Response(status: 204);
    }
}
