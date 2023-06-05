<?php

namespace App\Controllers\Oauth;

use Modules\Cpf;
use Http\Request;
use Modules\Nome;
use Http\Response;
use Modules\Email;
use Controller\Controller;
use App\Models\Oauth\Usuario\LoginModel;
use App\Models\Oauth\Fenae\UrlLoginModel;
use App\Models\Oauth\Usuario\SalvarModel;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Models\Oauth\Fenae\PegarTokenModel;

final class FenaeController extends Controller
{
    public function paginaLogin()
    {
        //843.573.208-87
        //Hxm7O7i9Ry
        $Login = new UrlLoginModel();
        return new Response(url: $Login->pegarUrlLogin());
    }
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
            empresa: 153,
            nome: $usuario['nome'],
            cpf: $usuario['cpf'],
            email: $usuario['email'],
            grupo: $usuario['grupo']
        );
        return new Response(url: $Login->pegarLink());
    }
}
