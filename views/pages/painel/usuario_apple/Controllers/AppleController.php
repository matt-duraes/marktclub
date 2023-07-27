<?php

namespace Painel\UsuarioApple\Controllers;

use Helpers\ApiHelper;
use Controller\Controller;

final class AppleController extends Controller
{
    public function index()
    {
        return view('usuario_apple.index', [
            'appTitulo' => 'USUÁRIO APPLE',
            'app'       => 'usuario-apple',
            'tipo'      => 'salvar',
        ]);
    }

    public function postSalvar()
    {
        (new ApiHelper(token: true))
            ->validar('Erro ao salvar usuários para a Apple')
            ->post('/usuario-cliente/apple');

        return mensagemSucesso(['id' => uuid()], status: 201);
    }
}
