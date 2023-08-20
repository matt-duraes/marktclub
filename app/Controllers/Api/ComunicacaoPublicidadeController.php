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
    public function getListar(Request $request): Response
    {
        $Publicidade = new PublicidadeModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            titulo: $request->titulo,
            dataInicio: new Data($request->data_inicio),
            dataFinal: new Data($request->data_final),
            status: new Status($request->status),
            publicado: new Botao($request->publicado),
            tipo: new Tipo($request->tipo),
            ordem: new Ordem($request->ordem)
        );
        return mensagemSucesso(
            $Publicidade->listarDados()
        );
    }

    public function getBuscar(string $id): Response
    {
        $Publicidade = new PublicidadeEntity();
        $Publicidade->uuid($id);

        return $this->retornoPadrao($Publicidade);
    }

    public function postSalvar(Request $request): Response
    {
        $Publicidade = new PublicidadeEntity();
        $Publicidade->set(lista: $request->dado());
        $Publicidade->salvar();

        return $this->retornoPadrao($Publicidade, 201);
    }

    private function retornoPadrao(PublicidadeEntity $Publicidade, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                $Publicidade,
                lista: [
                    'id', 'parceiro', 'titulo', 'imagem_desktop', 'imagem_mobile', 'data_inicio', 'data_final',
                    'data_criacao', 'link', 'tipo', 'publicado', 'status'
                ]
            ),
            status: $status
        );
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
}
