<?php

namespace App\Middlewares\Painel;

use Http\Response;
use Helpers\AuthHelper;
use PainelApp\login\Models\PainelModel;
use PainelApp\login\Models\LoginRefreshModel;
use PainelApp\login\Models\BuscarUsuarioModel;
use PainelApp\login\Models\AutenticarUsuarioModel;

final class AuthMiddleware
{
    public function logado(): bool|Response
    {
        $retorno = $this->verificarSeEstaLogado();
        if (!$retorno && cookieExiste('FWT')) {
            return $this->fazerLoginUsuario();
        }
        return $retorno;
    }

    private function verificarSeEstaLogado(): bool
    {
        $retorno = (new AuthHelper)->validar();
        if (
            true === $retorno ||
            !sessaoExiste('TOKEN') ||
            !sessaoExiste('TOKEN_EXPIRE') ||
            agora() >= sessao('TOKEN_EXPIRE')
        ) {
            return false;
        }
        return true;
    }

    private function fazerLoginUsuario()
    {
        try {
            $Login = new LoginRefreshModel(
                refreshToken: base64Decode(cookie('FWT'))
            );
            new AutenticarUsuarioModel(
                Login: $Login
            );
            new BuscarUsuarioModel();
            new PainelModel();

            return true;
        } catch (\Throwable) {
            return $this->usuarioNaoLogado();
        }
    }

    private function usuarioNaoLogado()
    {
        cookieDeletar('FWT');
        if (METODO == 'GET' && CONTENT_TYPE != 'application/json') {
            return new Response(url: LINK . '/login');
        }

        return new Response(json: [
            'status' => 'deslogado'
        ], status: 401);
    }
}
