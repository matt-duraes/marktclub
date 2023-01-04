<?php

namespace App\Controllers\Api;

use Http\Request;
use Controller\Controller;
use App\Models\Api\AdminEmpresa\EmpresaModel;

final class AdminEmpresaController extends Controller
{
    public function getSelect(Request $request)
    {
        $App = new EmpresaModel();
        $dado = $App->pegarSelect(
            indice: 'cod',
            valor: 'nome_fantasia',
            where: [
                ['status', 'in', [1, 2]]
            ],
            titulo: $request->titulo
        );

        return mensagemSucesso($dado);
    }
}
