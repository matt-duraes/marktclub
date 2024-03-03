<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\Geral\Status;
use App\Models\Api\OrdenarModel;
use App\Classes\PublicacaoDiretoria\Ordem;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerOrdenarInterface;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\PublicacaoDiretoria\DiretoriaModel;
use App\Models\Api\PublicacaoDiretoria\DiretoriaEntity;

final class PublicacaoDiretoriaController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface,
    ControllerOrdenarInterface
{
    public function getListar(Request $request): Response
    {
        $Diretoria = new DiretoriaModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            pesquisa: $request->pesquisa,
            status: new Status($request->status)
        );

        return mensagemSucesso($Diretoria->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Diretoria = new DiretoriaEntity();
        $Diretoria->idSlug($id);

        return $this->retornoSucesso($Diretoria);
    }

    public function postSalvar(Request $request): Response
    {
        $dado = $request->dado();
        $dado['texto'] = $request->getPost('texto', html: false);
        $Diretoria = new DiretoriaEntity();
        $Diretoria->set(lista: $dado);
        $Diretoria->salvar();

        return $this->retornoSucesso($Diretoria, 201);
    }

    private function retornoSucesso(DiretoriaEntity $Diretoria, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                $Diretoria,
                lista: [
                    'nome', 'texto', 'cargo', 'data_criacao', 'data_atualizacao', 'imagem', 'status'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $dado = $request->dado();
        if (!$request->vazio('texto')) {
            $dado['texto'] = $request->getPut('texto', html: false);
        }

        $Diretoria = new DiretoriaEntity();
        $Diretoria->idSlug($id);
        $Diretoria->set(lista: $dado);
        $Diretoria->salvar();

        return new Response(status: 204);
    }

    public function putOrdenar(Request $request): Response
    {
        new OrdenarModel(
            id: jsonDecode($request->id, true, true),
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            tabela: TABELA_PUBLICACAO_DIRETORIA
        );

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Diretoria = new DiretoriaEntity();
        $Diretoria->uuid($id);
        $Diretoria->destruir();

        return new Response(status: 204);
    }
}
