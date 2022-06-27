<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\Painel\MenuModel;
use App\Models\Api\Painel\StatusModel;
use App\Models\Api\Painel\ConfiguracaoEntity;

final class PainelController extends Controller
{
    public function getPermissao(): Response
    {
        $Configuracao = new ConfiguracaoEntity();
        return mensagemSucesso($Configuracao->permissao);
    }

    public function getConfiguracao(): Response
    {
        $Configuracao = new ConfiguracaoEntity();
        return mensagemSucesso($Configuracao->configuracao);
    }

    public function getMenu(): Response
    {
        $Menu = new MenuModel;
        return mensagemSucesso($Menu->listarDados());
    }
    public function getCampoObrigatorio(Request $request): Response
    {
        $Configuracao = new ConfiguracaoEntity();
        $campo = $Configuracao->campo_obrigatorio;

        if (!empty($request->app)) {
            $campo = is_array($campo) && array_key_exists($request->app, $campo) ? $campo[$request->app] : [];
        }
        return mensagemSucesso($campo);
    }
    public function getCampoPermitido(Request $request): Response
    {
        $Configuracao = new ConfiguracaoEntity();
        $campo = $Configuracao->campo_permitido;

        if (!empty($request->app)) {
            $campo = is_array($campo) && array_key_exists($request->app, $campo) ? $campo[$request->app] : [];
        }
        return mensagemSucesso($campo);
    }

    public function getTrabalhoOrgao(): Response
    {
        $Status = new StatusModel;
        $lista = $Status->listar('usuario_cliente', 'trabalho_orgao');

        return mensagemSucesso($lista);
    }

    public function getTrabalhoCargo(): Response
    {
        $Status = new StatusModel;
        $lista = $Status->listar('usuario_cliente', 'trabalho_cargo');

        return mensagemSucesso($lista);
    }

    public function getTipoPagamento(): Response
    {
        $Status = new StatusModel;
        $lista = $Status->listar('usuario_cliente', 'tipo_pagamento');

        return mensagemSucesso($lista);
    }

    public function getUsuarioSituacao(): Response
    {
        $Status = new StatusModel;
        $lista = $Status->listar('usuario_cliente', 'situacao');

        return mensagemSucesso($lista);
    }
}
