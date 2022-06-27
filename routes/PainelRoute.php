<?php

use Route\Route;

require_once ROOT . '/views/pages/painel/login/Routes/LoginRoute.php';
require_once ROOT . '/views/pages/painel/perfil/Routes/PerfilRoute.php';
require_once ROOT . '/views/pages/painel/demanda_tarefa/Routes/DemandaRoute.php';
require_once ROOT . '/views/pages/painel/album/Routes/AlbumRoute.php';
require_once ROOT . '/views/pages/painel/data_policy/Routes/DataPolicyRoute.php';
require_once ROOT . '/views/pages/painel/agenda/Routes/AgendaRoute.php';
require_once ROOT . '/views/pages/painel/relatorio/Routes/RelatorioRoute.php';
require_once ROOT . '/views/pages/painel/usuario_tabela/Routes/TabelaRoute.php';

/*
|--------------------------------------------------------------------------
| APP GERAL
|--------------------------------------------------------------------------
*/

Route
    ::middleware(
        classe: App\Middlewares\AuthMiddleware::class,
        action: 'logado',
        parametro: [Painel\Login\Models\RelogarUsuarioModel::class, 'fazerLogin']
    )

    // INDEX
    ::nome('index')
    ::controller(App\Controllers\Painel\IndexController::class)
    ::grupo(function () {
        Route::nome('index')::view('/');
    }, true)

    // DASHBOARD
    ::nome('dashboard')
    ::controller(App\Controllers\Painel\DashboardController::class)
    ::grupo(function () {
        Route::nome('index')::view('/dashboard');
        Route::nome('acesso')::get('/dashboard/acesso');
        Route::nome('usuario')::get('/dashboard/usuario');
    }, true)

    // APP
    ::controller(App\Controllers\Painel\AppController::class)
    ::grupo(function () {
        Route
            ::action('index')
            ::request(['!pesquisa', '!filtro', '!ordem', '!pagina'])
            ::view('/app/{app}');

        Route
            ::action('ajax')
            ::request('*')
            ::post('/app/ajax/{app}');

        Route
            ::action('visualizar')
            ::request('*')
            ::view('/app/visualizar/{app}/{uuid}');

        Route
            ::action('status')
            ::request(['id', 'app', 'status'])
            ::post('/app/status');

        Route
            ::action('add')
            ::view('/app/add/{app}');

        Route
            ::action('editar')
            ::request('*')
            ::view('/app/editar/{app}/{uuid}');

        Route
            ::action('salvar')
            ::request('*')
            ::request('*', 'files')
            ::post('/app/salvar/{app}');

        Route
            ::action('filtrar')
            ::request(['!ordem'])
            ::view('/app/filtrar/{app}');

        Route
            ::action('download')
            ::request(['pesquisa', 'filtro', 'ordem'])
            ::view('/app/download/{app}');

        Route
            ::action('download')
            ::request(['termo', 'senha', 'campo', 'ordem', 'pesquisa', 'filtro'])
            ::post('/app/download/{app}');

        Route
            ::action('removerFiltro')
            ::request(['ordem', 'filtro', 'indice'])
            ::view('/app/remover-filtro/{app}');

        Route
            ::action('filtrar')
            ::request('*')
            ::post('/app/filtrar/{app}');

        Route
            ::action('deletar')
            ::request(['id', 'hash_validacao'])
            ::post('/app/deletar/{app}');

        Route
            ::action('ordem')
            ::request(['id', 'pagina', 'hash_validacao'])
            ::post('/app/ordem/{app}');
    }, true)

    // UPLOAD
    ::controller(App\Controllers\Painel\UploadController::class)
    ::grupo(function () {
        Route
            ::action('extensao')
            ::request(['grupo'])
            ::post('/upload/extensao');
        Route
            ::action('estruturaDiretorio')
            ::request(['grupo'])
            ::post('/upload/estrutura-diretorio');
        Route
            ::action('criarDiretorio')
            ::request(['grupo_inicial', 'grupo_atual', 'nome'])
            ::post('/upload/criar-diretorio');
        Route
            ::action('renomearDiretorio')
            ::request(['grupo_inicial', 'grupo_atual', 'nome'])
            ::post('/upload/renomear-diretorio');
        Route
            ::action('deletarDiretorio')
            ::request(['grupo_inicial', 'grupo_atual'])
            ::post('/upload/deletar-diretorio');

        Route
            ::action('buscar')
            ::request(['pagina', 'pesquisa', 'grupo_inicial', 'grupo_atual'])
            ::post('/upload/buscar');

        Route
            ::action('salvar')
            ::request(['grupo_inicial', 'grupo_atual'])
            ::request(['arquivo'], 'files')
            ::post('/upload/salvar');

        Route
            ::action('mover')
            ::request(['id', 'nome', 'grupo_inicial', 'grupo_atual', 'grupo_destino'])
            ::post('/upload/mover');

        Route
            ::action('renomear')
            ::request(['grupo_atual', 'grupo_inicial', 'id', 'nome'])
            ::post('/upload/renomear');

        Route
            ::action('editar')
            ::request(['id', 'tipo', 'nome', 'largura', 'altura', 'corte_largura', 'corte_altura', 'x', 'y'])
            ::post('/upload/editar');

        Route
            ::action('deletar')
            ::request(['grupo_atual', 'grupo_inicial', 'id'])
            ::post('/upload/deletar');
    }, true)

    ::controller(App\Controllers\Painel\HistoricoController::class)
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::request(['app', 'relacionado', 'mensagem'])
            ::post('/historico');
        Route
            ::nome('listar')
            ::request(['pagina', 'app', 'relacionado', '!data_de', '!data_ate'])
            ::get('/historico');
        Route
            ::nome('deletar')
            ::delete('/historico/{id}');
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
