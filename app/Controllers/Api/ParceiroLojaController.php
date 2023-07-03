<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\ParceiroLoja\LojaModel;
use App\Models\Api\ParceiroLoja\LojaEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;

final class ParceiroLojaController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface
{
    public function getBuscar(string $id): Response
    {
        $Parceiro = new LojaEntity();
        $Parceiro->idSlug($id);
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Parceiro,
                lista: [
                    'titulo', 'link_logo', 'texto_desconto',
                    'texto_procedimento', 'status', 'favorito', 'descricao', 'banner',
                    'desconto_texto', 'procedimento_texto','desconto','voucher_texto'
                ]
            )
        );

    }

    public function getListar(Request $request): Response
    {
        $Parceiro = new LojaModel($request);

        if($request->favorito == 1) {
            $dado = $Parceiro->pegarRetornoFavorito();
        } else {
            $dado = $Parceiro->pegarRetorno();
        }
        return mensagemSucesso($dado);
    }

}
