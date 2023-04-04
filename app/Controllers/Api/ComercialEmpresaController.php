<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\ComercialEmpresa\Helper;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSelectInterface;
use App\Models\Api\ComercialEmpresa\EmpresaModel;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;

final class ComercialEmpresaController extends Controller implements
    ControllerBuscarInterface,
    ControllerSelectInterface,
    ControllerListarInterface
{
    public function getListar(Request $request): Response
    {
        $Empresa = new EmpresaModel($request);

        return mensagemSucesso(
            dado: $Empresa->listarDados(),
            criptografar: Helper::CRIPTOGRAFAR,
        );
    }

    public function getSelect(Request $request): Response
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

    public function getBuscar(string $id): Response
    {
        $Empresa = new EmpresaEntity();
        $Empresa->uuid($id);

        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Empresa,
                lista: [
                    'titulo', 'nome_fantasia', 'razao_social', 'cnpj', 'slug', 'imagem',
                    'responsavel_nome', 'responsavel_cpf', 'responsavel_email', 'responsavel_telefone',
                    'estado_principal', 'valor_pago', 'valor_pib', 'renda_media', 'valor_usuario', 'produto_clube',
                    'produto_ios', 'produto_android', 'produto_site', 'tipo_pagamento', 'status'
                ]
            ),
            criptografar: Helper::CRIPTOGRAFAR
        );
    }
}
