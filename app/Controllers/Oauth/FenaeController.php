<?php

namespace App\Controllers\Oauth;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Oauth\Fenae\UrlLoginModel;
use App\Models\Oauth\Usuario\SalvarModel;
use App\Models\Oauth\Fenae\UrlLogoutModel;
use App\Models\Oauth\Fenae\PegarTokenModel;

final class FenaeController extends Controller
{
    private int $idEmpresa = 153;

    /**
     * MANDAR PARA A PÁGINA DE LOGIN
     */
    public function paginaLogin()
    {
        $Login = new UrlLoginModel();
        return new Response(url: $Login->pegarUrlLogin());
    }

    /*
    |--------------------------------------------------------------------------
    | SALVAR O USUÁRIO E MANDA PARA O CLUBE
    |--------------------------------------------------------------------------
    */
    public function pegarToken(Request $request)
    {
        try {
            $Token = new PegarTokenModel(
                code: $request->code,
                state: $request->state
            );
        } catch (\Throwable) {
            $this->limparTemporario();
            return new Response(url: env('FENAE_CLUBE') . '?erro=login-erro');
        }
        if (!$Token->podeLogar()) {
            $this->limparTemporario();
            return new Response(url: $Token->pegarLinkErro());
        }
        try {
            $usuario = $Token->pegarUsuario();
            $Login = new SalvarModel(
                empresa: $this->idEmpresa,
                nome: $usuario['nome'],
                cpf: $usuario['cpf'],
                email_pessoal: $usuario['email'],
                grupo: $usuario['grupo']
            );
            $this->limparTemporario();
            return new Response(url: $Login->pegarLink());
        } catch (\Throwable) {
            $this->limparTemporario();
            return new Response(url: env('FENAE_CLUBE') . '?erro=login-erro');
        }
    }

    /**
     * LOGOUT ENVIADO PELA FENAE
     */
    public function delogarPelaFenae()
    {
        $this->limparLogin();
        return new Response(url: env('FENAE_LOGOUT_CLUBE'), status: 302);
    }

    /**
     * QUANDO O USUÁRIO CLICA EM SAIR NO CLUBE
     */
    public function deslogarPeloUsuario()
    {
        $Token = new UrlLogoutModel();
        $url = $Token->pegarUrlLogout();
        $this->limparLogin();
        return new Response(url: $url, status: 302);
    }

    public function usuarioDeslogou()
    {
        return new Response(url: env('FENAE_CLUBE'));
    }

    /**
     * LIMPA AS SESSOES E COOKIES
     */
    private function limparLogin()
    {
        $this->limparTemporario();
        if (cookieExiste('MKCLTI')) {
            cookieDeletar('MKCLTI');
        }
    }

    private function limparTemporario()
    {
        if (cookieExiste('MKCTC')) {
            cookieDeletar('MKCTC');
        }
        if (cookieExiste('MKCLCO')) {
            cookieDeletar('MKCLCO');
        }
        if (cookieExiste('MKCLOE')) {
            cookieDeletar('MKCLOE');
        }
    }
}
