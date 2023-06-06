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
        //843.573.208-87
        //Hxm7O7i9Ry
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
            return new Response(url: env('FENAE_LOGOUT_REDIRECT_URI'));
        }
        $usuario = $Token->pegarUsuario();
        $Login = new SalvarModel(
            empresa: $this->idEmpresa,
            nome: $usuario['nome'],
            cpf: $usuario['cpf'],
            email: $usuario['email'],
            grupo: $usuario['grupo']
        );
        return new Response(url: $Login->pegarLink());
    }

    /**
     * LOGOUT ENVIADO PELA FENAE
     */
    public function delogarPelaFenae()
    {
        $this->limparLogin();
        return new Response(url: env("FENAE_LOGOUT_CLUBE"), status: 302);
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
        return new Response(url: env('FENAE_LOGIN'));
    }

    /**
     * LIMPA AS SESSOES E COOKIES
     */
    private function limparLogin()
    {
        if (sessaoExiste('FENAE_LOGIN_STATE')) {
            sessaoDeletar('FENAE_LOGIN_STATE');
        }
        if (sessaoExiste('FENAE_LOGIN_PKCE')) {
            sessaoDeletar('FENAE_LOGIN_PKCE');
        }
        if (cookieExiste('MKCLTI')) {
            cookieDeletar('MKCLTI');
        }
    }
}
