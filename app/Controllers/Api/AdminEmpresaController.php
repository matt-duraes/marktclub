<?php

namespace App\Controllers\Api;

use Http\Request;
use Controller\Controller;
use App\Classes\AdminEmpresa\Helper;
use App\Models\Api\AdminEmpresa\EmpresaModel;
use App\Models\Api\AdminEmpresa\EmpresaEntity;
use System\Interface\ControllerBuscarInterface;

final class AdminEmpresaController extends Controller
implements ControllerBuscarInterface
{
    public function getSelect(Request $request)
    {
        $Empresa = new EmpresaModel();
        $dado = $Empresa->pegarSelect(
            indice: 'cod',
            valor: 'nome_fantasia',
            where: [
                ['status', 'in', [1, 2]]
            ],
            titulo: $request->titulo
        );

        return mensagemSucesso($dado);
    }

    public function getBuscar(string $id)
    {
        $Empresa = new EmpresaEntity();
        $Empresa->id($id);

        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Empresa,
                lista: ['nome_fantasia', 'cnpj', 'slug', 'imagem', 'status']
            ),
            criptografar: Helper::CRIPTOGRAFAR
        );
    }
}
