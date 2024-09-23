<?php

namespace App\Middlewares\Painel;

use Http\Request;
use Http\Response;
use Helpers\AuthHelper;
use PainelApp\login\Models\LoginRefreshModel;
use PainelApp\login\Models\LoginAutorizadoModel;

final class AuthMiddleware
{
    private array $token;

    public function __construct()
    {
        $token = cookieExiste('FWT') ? base64Decode(cookie('FWT')) : [];
        $this->token = is_array($token) ? $token : [];
        $this->validarAmbiente();
    }

    public function logado(): bool|Response
    {
        $retorno = $this->verificarSeEstaLogado();
        if (!$retorno && !empty($this->token) && dataBanco($this->token['data']) == hoje()) {
            return $this->fazerLoginUsuario();
        } elseif (!$retorno) {
            return $this->usuarioNaoLogado();
        }
        return true;
    }

    private function verificarSeEstaLogado(): bool
    {
        $retorno = (new AuthHelper())->validar();
        if (
            false === $retorno ||
            !sessaoExiste('TOKEN') ||
            !sessaoExiste('TOKEN_EXPIRE') ||
            agora() >= sessao('TOKEN_EXPIRE') ||
            empty($this->token) ||
            dataBanco($this->token['data']) != hoje()
        ) {
            return false;
        }
        return true;
    }

    private function fazerLoginUsuario()
    {
        try {
            $Login = new LoginRefreshModel(
                refreshToken: $this->token['token']
            );
            new LoginAutorizadoModel(Login: $Login);
            return true;
        } catch (\Throwable $e) {
            return $this->usuarioNaoLogado();
        }
    }

    private function usuarioNaoLogado()
    {
        cookieDeletar('FWT');
        if (METODO == 'GET' && CONTENT_TYPE != 'application/json') {
            return new Response(url: LINK . '/login' . $this->pegarLocation());
        }
        return new Response(json: [
            'status' => 'deslogado'
        ], status: 401);
    }

    private function pegarLocation(): string
    {
        if (!defined('ROTA_VIEW') || !ROTA_VIEW) {
            return '';
        }
        return '?location=' . base64Encode(LINK . URI, true);
    }

    private function validarAmbiente()
    {
        $titulo = '';
        $url = (new Request())->url();
        if (strpos($url, 'painelhmlprod') !== false) {
            $titulo = 'HMLPROD';
        } else if (strpos($url, 'painelhml') !== false) {
            $titulo = 'HML';
        } else if (strpos($url, 'localhost') !== false) {
            $titulo = 'DEV';
        }

        define('AMBIENTE_ACESSO', $titulo);
    }
}
