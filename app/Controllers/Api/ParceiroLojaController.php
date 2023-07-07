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
                    'titulo', 'link_logo', 'link_capa_mobile', 'link_capa_desktop', 'texto_desconto',
                    'texto_voucher', 'texto_procedimento', 'texto_descricao', 'procedimento', 'favorito', 'status'
                ]
            )
        );
    }

    public function getListar(Request $request): Response
    {
        $Parceiro = new LojaModel($request);
        return mensagemSucesso($Parceiro->listarDados());
    }
}
