<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\Geral\Status;
use App\Classes\TextoClube\Tipo;
use App\Models\Api\OrdenarModel;
use App\Classes\TextoClube\Ordem;
use App\Models\Api\TextoClube\TextoModel;
use App\Models\Api\TextoClube\TextoEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class TextoClubeController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Texto = new TextoModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            empresa: $request->empresa,
            tipo: new Tipo($request->tipo),
            ordem: new Ordem($request->ordem),
            status: new Status($request->status)
        );

        return mensagemSucesso($Texto->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Texto = new TextoEntity();
        $Texto->uuid($id);

        return $this->retornoPadrao($Texto);
    }

    public function postSalvar(Request $request): Response
    {
        $dado = $request->dado();
        $dado['texto'] = $request->getPost('texto', html: false);
        $Texto = new TextoEntity();
        $Texto->set(lista: $dado);
        $Texto->salvar();

        return $this->retornoPadrao($Texto, 201);
    }

    private function retornoPadrao(TextoEntity $Texto, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Texto,
                lista: ['titulo', 'texto', 'data_criacao', 'data_atualizacao', 'ordem', 'status']
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
        $Texto = new TextoEntity();
        $Texto->uuid($id);
        $Texto->set(lista: $dado);
        $Texto->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Texto = new TextoEntity();
        $Texto->uuid($id);
        $Texto->destruir();

        return new Response(status: 204);
    }

    public function putOrdenar(Request $request)
    {
        new OrdenarModel(
            id: jsonDecode($request->id, true, true),
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            tabela: TABELA_TEXTO_CLUBE
        );

        return new Response(status: 204);
    }
}
