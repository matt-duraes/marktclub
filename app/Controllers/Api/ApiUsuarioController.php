<?php

namespace App\Controllers\Api;

use Controller\Controller;
use App\Models\Api\ApiUsuario\UsuarioModel;

final class ApiUsuarioController extends Controller
{
    public function getSelect()
    {
        $Usuario = new UsuarioModel();

        $dado = $Usuario->pegarSelect();
        return mensagemSucesso($dado);
    }
}
