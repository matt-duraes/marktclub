<?php

namespace App\Controllers\Api;

use App\Controllers\Api\Trait\ParceiroTrait;
use App\Models\Api\ParceiroFavorito\FavoritoEntity;
use App\Models\Api\ParceiroFavorito\FavoritoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class ParceiroFavoritoController extends Controller implements
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerDeletarInterface
{
    use ParceiroTrait;

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $FavoritoModel = new FavoritoModel();
        return mensagemSucesso($FavoritoModel->listarDado());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $FavoritoEntity = new FavoritoEntity($request->parceiro);
        $FavoritoEntity->salvar();
        return mensagemSucesso([
            'id' => $FavoritoEntity->id
        ], 201);
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        $parceiro = $this->pegarParceiro($id);
        $FavoritoEntity = new FavoritoEntity();
        $FavoritoEntity->buscar([
            ['id_parceiro_loja', $parceiro->get('id')],
            ['id_usuario_cliente', TOKEN['usuario']->id]
        ]);
        $FavoritoEntity->destruir();
        return new Response(status: 204);
    }
}
