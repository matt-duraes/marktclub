<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\ParceiroLoja\Categoria;
use App\Models\Api\ParceiroSubcategoria\SelectModel;

final class ParceiroSubcategoriaController extends Controller
{
    public function getSelect(Request $request): Response
    {
        $Empresa = new SelectModel(
            categoria: new Categoria($request->categoria),
            titulo: $request->titulo
        );
        return mensagemSucesso($Empresa->listarDados());
    }
}
