<?php

use Route\Route;

Route
    ::middleware(
        classe: App\Middlewares\AuthMiddleware::class,
        action: 'logado',
        parametro: [Painel\Login\Models\RelogarUsuarioModel::class, 'fazerLogin']
    )
    ::nome('perfil')
    ::controller(Painel\Perfil\Controllers\PerfilController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/perfil');

        Route
            ::nome('dado')
            ::view('/perfil/dado');

        Route
            ::nome('atualizar_dado')
            ::action('dado')
            ::request([
                'nome', 'data_nascimento', 'genero', 'email_pessoal', 'telefone_trabalho',
                'telefone_pessoal'
            ])
            ::post('/perfil/dado');

        Route
            ::nome('validar_senha')
            ::action('validarSenha')
            ::request(['senha'])
            ::post('/perfil/validar-senha');

        Route
            ::nome('senha')
            ::view('/perfil/senha');

        Route
            ::nome('atualizar_senha')
            ::action('senha')
            ::request(['hash_validacao', 'senha_atual', 'senha_nova', 'senha_repetir'])
            ::post('/perfil/senha');

        Route
            ::nome('social')
            ::request(['hash_validacao', 'id', 'token', 'tipo'])
            ::post('/perfil/social');
    });
