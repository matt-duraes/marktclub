<?php

use Route\Route;

Route::middleware(
    classe: App\Middlewares\Painel\AuthMiddleware::class,
    action: 'logado',
)::middleware(
    App\Middlewares\Painel\PermissaoMiddleware::class,
    'validar',
    ['tabela_usuario_salvar']
)::controller(Painel\UsuarioTabela\Controllers\TabelaController::class)::nome('tabela_salvar')::grupo(function () {
    Route::action('salvar')::view('/tabela/salvar');

    Route::action('analisarSalvar')::request(['arquivo'], 'files')::post('/tabela/analisar-salvar');

    Route::action('salvar')::request(['hash'])::post('/tabela/salvar');
});

Route::middleware(
    classe: App\Middlewares\AuthMiddleware::class,
    action: 'logado',
    parametro: [Painel\Login\Models\RelogarUsuarioModel::class, 'fazerLogin']
)::middleware(
    App\Middlewares\Painel\PermissaoMiddleware::class,
    'validar',
    ['tabela_usuario_bloquear']
)::controller(Painel\UsuarioTabela\Controllers\TabelaController::class)::nome('tabela_bloquear')::grupo(function () {
    Route::action('bloquear')::view('/tabela/bloquear');

    Route::action('analisarBloquear')::request(['arquivo'], 'files')::post('/tabela/analisar-bloquear');

    Route::action('bloquear')::request(['hash'])::post('/tabela/bloquear');
});
