<?php

use Route\Route;

require_once ROOT . '/views/pages/painel/demanda_geral/Routes/DemandaRoute.php';
require_once ROOT . '/views/pages/painel/album_galeria/Routes/AlbumRoute.php';
require_once ROOT . '/views/pages/painel/relatorio/Routes/RelatorioRoute.php';
require_once ROOT . '/views/pages/painel/tabela_usuario/Routes/TabelaRoute.php';
require_once ROOT . '/views/pages/painel/comercial_prospeccao/Routes/ComercialProspeccaoRoute.php';
require_once ROOT . '/views/pages/painel/usuario_apple/Routes/UsuarioAppleRoute.php';

/*
|--------------------------------------------------------------------------
| APP GERAL
|--------------------------------------------------------------------------
*/

Route
    ::middleware(
        classe: App\Middlewares\Painel\AuthMiddleware::class,
        action: 'logado',
    )

    // INDEX
    ::nome('index')::controller(App\Controllers\Painel\IndexController::class)::grupo(function () {
        Route::nome('index')::view('/');
    }, true)

    // DASHBOARD
    ::nome('dashboard')::controller(App\Controllers\Painel\DashboardController::class)::grupo(function () {
        Route::nome('index')::view('/dashboard');
    }, true)

    // SISTEMA DE PAGAMENTO USUARIO
    ::controller(App\Controllers\Painel\SolicitacaoVoucherController::class)::grupo(function () {
        Route::nome('voucher')::view('/solicitacao-voucher/gerar/{parceiro}/{usuario}');
    }, true)
    // SISTEMA DE PAGAMENTO USUARIO
    ::controller(App\Controllers\Painel\UsuarioPagamentoController::class)::grupo(function () {
        Route::nome('salvar')::request(['hash_validacao', 'data', 'valor', 'usuario'])::post('/usuario-pagamento');
        Route::nome('deletar')::delete('/usuario-pagamento/{id}');
    });
