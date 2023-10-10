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

    public function getUploadGrupo(): Response
    {
        $Configuracao = new ConfiguracaoEntity();
        return mensagemSucesso($Configuracao->upload_grupo);
    }

    public function getMenu(): Response
    {
        $Menu = new MenuModel();
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

    public function getChavePublica()
    {
        return mensagemSucesso(['chave' => TOKEN['app']->chave_publica]);
    }

    public function getChavePrivada()
    {
        return mensagemSucesso(['chave' => TOKEN['app']->chave_privada]);
    }
}
