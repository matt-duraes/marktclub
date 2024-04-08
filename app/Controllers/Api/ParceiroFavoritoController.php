<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Controllers\Api\Trait\ParceiroTrait;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\ParceiroFavorito\FavoritoModel;
use App\Models\Api\ParceiroFavorito\FavoritoEntity;

final class ParceiroFavoritoController extends Controller implements
    ControllerSalvarInterface,
    ControllerDeletarInterface,
    ControllerListarInterface
{
    use ParceiroTrait;

    public function getListar(Request $request): Response
    {
        $Parceiro = new FavoritoModel();
        return mensagemSucesso($Parceiro->listarDado());
    }

    public function postSalvar(Request $request): Response
    {
        $Parceiro = $this->parceiro($request->parceiro);
        $Favorito = new FavoritoEntity(Parceiro: $Parceiro);
        $Favorito->salvar();

        return mensagemSucesso([
            'id' => $Favorito->id
        ], status: 201);
    }

    public function deleteDeletar(string $id): Response
    {
        $Parceiro = $this->parceiro($id);
        $Favorito = new FavoritoEntity();
        try {
            $Favorito->buscar([
                ['id_parceiro_loja', $Parceiro->get('id')],
                ['id_usuario_cliente', TOKEN['usuario']->id]
            ]);
            $Favorito->destruir();
        } catch (\Throwable) {
        }

        return new Response(status: 204);
    }

    private function parceiro($id): LojaEntity
    {
        return $this->pegarParceiro(
            $id,
            obrigatorio: true,
            mensagemVazio: 'Não foi passado um parceiro.',
            mensagemErro: 'Parceiro não foi encontrado.'
        );
    }
}
