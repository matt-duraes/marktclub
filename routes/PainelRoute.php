<?php

use Route\Route;
use App\Middlewares\Painel\AuthMiddleware;

require_once ROOT . '/views/pages/painel/demanda/Routes/DemandaRoute.php';
require_once ROOT . '/views/pages/painel/album_galeria/Routes/AlbumRoute.php';
require_once ROOT . '/views/pages/painel/relatorio/Routes/RelatorioRoute.php';
require_once ROOT . '/views/pages/painel/usuario_tabela/Routes/TabelaRoute.php';

/*
|--------------------------------------------------------------------------
| APP GERAL
|--------------------------------------------------------------------------
*/

Route
    // ::middleware(
    //     classe: AuthMiddleware::class,
    //     action: 'logado',
    // )

    // INDEX
    ::nome('index')
    ::controller(App\Controllers\Painel\IndexController::class)
    ::grupo(function () {
        Route::nome('index')::view('/');
    }, true)

    // DASHBOARD
    ::nome('dashboard')
    ::controller(App\Controllers\Painel\DashboardController::class)
    ::middleware(
        classe: AuthMiddleware::class,
        action: 'logado',
    )
    ::grupo(function () {
        Route::nome('index')::view('/dashboard');
    }, true)

    // SISTEMA DE PAGAMENTO USUARIO
    ::controller(App\Controllers\Painel\UsuarioPagamentoController::class)
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::request(['hash_validacao', 'data', 'valor', 'usuario'])
            ::post('/usuario-pagamento');

        Route
            ::nome('deletar')
            ::delete('/usuario-pagamento/{id}');
    });
