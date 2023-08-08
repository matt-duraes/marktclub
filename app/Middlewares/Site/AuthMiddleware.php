<?php

namespace App\Middlewares\Site;

use Http\Response;
use Helpers\AuthHelper;
use App\Models\Site\Login\LoginRefreshModel;

final class AuthMiddleware
{
    private array $token;

    public function __construct()
    {
        $token = cookieExiste('CLT') ? base64Decode(cookie('CLT')) : [];
        $this->token = is_array($token) ? $token : [];
    }

    public function deslogado(): bool|Response
    {
        if ($this->verificarSeEstaLogado()) {
            return new Response(url: LINK);
        }
        return true;
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
            !sessaoExiste('USUARIO') ||
            !sessaoExiste('CLUBE') ||
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
            new LoginRefreshModel(
                refreshToken: $this->token['token']
            );
            return true;
        } catch (\Throwable) {
            return $this->usuarioNaoLogado();
        }
    }

    private function usuarioNaoLogado()
    {
        (new AuthHelper());
        cookieDeletar('CLT');
        if (METODO == 'GET' && CONTENT_TYPE != 'application/json') {
            return new Response(url: LINK . '/login');
        }

        return new Response(json: [
            'status' => 'deslogado'
        ], status: 401);
    }
}
