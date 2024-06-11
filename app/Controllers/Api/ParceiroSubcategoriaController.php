<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\ParceiroLoja\Categoria;
use App\Models\Api\ParceiroSubcategoria\SelectModel;
use App\Models\Api\ParceiroSubcategoria\SubcategoriaModel;

final class ParceiroSubcategoriaController extends Controller
{
    public function getSelect(Request $request): Response
    {
        $Subcategoria = new SelectModel(
            categoria: new Categoria($request->categoria),
            titulo: $request->titulo
        );
        return mensagemSucesso($Subcategoria->listarDados());
    }

    public function getListar(Request $request): Response
    {
        $Subcategoria = new SubcategoriaModel($request);
        return mensagemSucesso($Subcategoria->listarDado());
    }
}
