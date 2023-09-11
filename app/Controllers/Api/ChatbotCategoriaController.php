<?php

namespace App\Controllers\Api;

use App\Models\Api\ChatbotCategoria\CategoriaEntity;
use App\Models\Api\ChatbotCategoria\CategoriaModel;
use App\Models\Api\ChatbotCategoria\PerguntasModel;
use Controller\Controller;
use Http\Request;
use Http\Response;
use Modules\Pagina;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class ChatbotCategoriaController extends Controller implements
    ControllerSalvarInterface, ControllerBuscarInterface, ControllerListarInterface
{
    public function getListar(Request $request): Response
    {
        $Categoria = new CategoriaModel(
            pagina: new Pagina($request->pagina)
        );
        return mensagemSucesso($Categoria->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Categoria = new CategoriaEntity();
        $Categoria->uuid($id);

        return $this->retornoPadrao($Categoria);
    }

    public function postSalvar(Request $request): Response
    {
        $Categoria = new CategoriaEntity();
        $Categoria->set(lista: $request->dado());
        $Categoria->salvar();

        return $this->retornoPadrao($Categoria, 201);
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Categoria = new CategoriaEntity();
        $Categoria->uuid($id);
        $Categoria->set(lista: $request->dado());
        $Categoria->salvar();

        return new Response(status: 204);
    }

    private function retornoPadrao(CategoriaEntity $Regra, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity($Regra, lista: [
                'categoria', 'pergunta', 'resposta', 'status'
            ]),
            status: $status
        );
    }
}
