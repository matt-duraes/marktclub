<?php

namespace App\Controllers\Api;

use Controller\Controller;
use App\Models\Api\AdminEmpresa\EmpresaModel;

final class AdminEmpresaController extends Controller
{
    public function getSelect()
    {
        $App = new EmpresaModel();
        $dado = $App->pegarSelect();

        return mensagemSucesso($dado);
    }
}
