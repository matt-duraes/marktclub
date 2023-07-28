<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\ParceiroLoja\Tipo;
use App\Models\Api\ParceiroLoja\LojaModel;
use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Models\Api\ParceiroLoja\SelectModel;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSelectInterface;

final class ParceiroLojaController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSelectInterface
{
    public function getSelect(Request $request): Response
    {
        $Parceiro = new SelectModel(
            titulo: $request->titulo,
            tipo: new Tipo($request->tipo)
        );
        return mensagemSucesso($Parceiro->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Parceiro = new LojaEntity();
        $Parceiro->idSlug($id);
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Parceiro,
                lista: [
                    'titulo', 'link_logo', 'link_capa_mobile', 'link_capa_desktop', 'texto_desconto', 'url',
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
