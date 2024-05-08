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
use App\Models\Api\OrdenarModel;
use App\Classes\ComunicacaoPublicidade\Tipo;
use App\Classes\ComunicacaoPublicidade\Ordem;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\ComunicacaoPublicidade\PublicidadeModel;
use App\Models\Api\ComunicacaoPublicidade\PublicidadeEntity;

final class ComunicacaoPublicidadeController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getBuscar(string $id): Response
    {
        $Publicidade = new PublicidadeEntity();
        $Publicidade->uuid($id);
        return $this->retornoPadrao($Publicidade);
    }

    private function retornoPadrao(PublicidadeEntity $Publicidade, int $status = 200)
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Publicidade,
                lista: [
                    'id', 'parceiro', 'titulo', 'imagem_desktop', 'imagem_mobile',
                    'data_inicio', 'data_final', 'data_criacao', 'link', 'tipo',
                    'publicado', 'status'
                ]
            ),
            $status
        );
    }

    public function getListar(Request $request): Response
    {
        $Publicidade = new PublicidadeModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->pesquisa,
            $request->empresa,
            $request->titulo,
            new Tipo($request->tipo),
            new Data($request->data_inicio),
            new Data($request->data_final),
            new Botao($request->publicado),
            new Status($request->status)
        );
        return mensagemSucesso($Publicidade->listarDados());
    }

    public function postSalvar(Request $request): Response
    {
        $Publicidade = new PublicidadeEntity();
        $Publicidade->set(lista: $request->dado());
        $Publicidade->salvar();
        return $this->retornoPadrao($Publicidade, 201);
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Publicidade = new PublicidadeEntity();
        $Publicidade->uuid($id);
        $Publicidade->set(lista: $request->dado());
        $Publicidade->salvar();
        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Publicidade = new PublicidadeEntity();
        $Publicidade->uuid($id);
        $Publicidade->destruir();
        return new Response(status: 204);
    }

    public function putOrdenar(Request $request)
    {
        new OrdenarModel(
            id: jsonDecode($request->id, true, true),
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            tabela: TABELA_COMUNICACAO_PUBLICIDADE
        );
        return new Response(status: 204);
    }
}
