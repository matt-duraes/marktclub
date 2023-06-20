<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\ComercialEmpresa\Helper;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerSelectInterface;
use App\Models\Api\ComercialEmpresa\EmpresaModel;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;
use System\Interface\ControllerAtualizarInterface;

final class ComercialEmpresaController extends Controller implements
    ControllerBuscarInterface,
    ControllerSelectInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface
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
                ['status', 'in', Helper::STATUS_LIBERADO],
                ['id_admin_empresa', 'null']
            ],
            titulo: $request->titulo
        );

        return mensagemSucesso($dado);
    }

    public function getBuscar(string $id): Response
    {
        $Empresa = new EmpresaEntity();
        $Empresa->uuid($id);
        return $this->retornoPadrao($Empresa);
    }

    public function postSalvar(Request $request): Response
    {
        $Empresa = new EmpresaEntity();
        $Empresa->set(lista: $request->dado());
        $Empresa->salvar();

        return $this->retornoPadrao($Empresa, 201);
    }

    private function retornoPadrao(EmpresaEntity $Empresa, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Empresa,
                lista: [
                    'equipe', 'finalidade_principal', 'finalidade_secundaria', 'imagem',
                    'titulo', 'nome_fantasia', 'razao_social', 'slug',
                    'site', 'responsavel_nome', 'responsavel_email', 'responsavel_telefone', 'responsavel_cpf',
                    'tipo_pagamento', 'valor_pago', 'renda_media', 'valor_pib', 'produto_clube',
                    'produto_ios', 'produto_android', 'produto_site', 'produto_webview', 'produto_api', 'cnpj',
                    'estado_principal', 'status', 'data_eleicao', 'email_dia', 'whatsapp_dia', 'rede_social_dia',
                    'contrato_data', 'contrato_prazo', 'contrato_renovacao', 'tipo_site', 'cadastro_usuario',
                    'comunicacao_email', 'comunicacao_whatsapp', 'comunicacao_rede_social', 'email_disparo',
                    'prospeccao_status', 'observacao_ti', 'observacao_comunicacao', 'observacao_financeiro',
                    'restricao_lista'
                ]
            ),
            criptografar: Helper::CRIPTOGRAFAR,
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Empresa = new EmpresaEntity();
        $Empresa->uuid($id);
        $Empresa->set(lista: $request->dado());
        $Empresa->salvar();

        return new Response(status: 204);
    }
}
