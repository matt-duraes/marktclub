<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Controllers\Api\Trait\ParceiroTrait;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\ParceiroFavorito\FavoritoEntity;

final class ParceiroFavoritoController extends Controller implements
    ControllerSalvarInterface,
    ControllerDeletarInterface
{
    use ParceiroTrait;

    public function postSalvar(Request $request): Response
    {
        $Parceiro = $this->pegarParceiro($request->parceiro, obrigatorio: true);
        $Favorito = new FavoritoEntity(Parceiro: $Parceiro);
        $Favorito->salvar();

        return mensagemSucesso([
            'id' => $Favorito->id
        ], status: 201);
    }

    public function deleteDeletar(string $id): Response
    {
        $Favorito = new FavoritoEntity();
        $Favorito->uuid($id);
        $Favorito->destruir();

        return new Response(status: 204);
    }
}
