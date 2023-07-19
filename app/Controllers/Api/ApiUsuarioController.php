<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\ApiUsuario\UsuarioEntity;
use System\Interface\ControllerSelectInterface;

final class ApiUsuarioController extends Controller implements
    ControllerSelectInterface
{
    public function getSelect(Request $request): Response
    {
        $Usuario = new UsuarioEntity();
        $dado = $Usuario->pegarSelect(
            indice: 'uuid',
            valor: 'nome_usuario',
            where: ['status', 1],
            titulo: $request->titulo
        );
        return mensagemSucesso($dado);
    }
}
