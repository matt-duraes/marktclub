<?php

use Route\Route;
use App\Middlewares\AuthMiddleware;
use PainelController\AppController;
use PainelController\UploadController;
use PainelController\HistoricoController;
use PainelApp\login\Controllers\LoginController;
use PainelApp\agenda\Controllers\AgendaController;
use PainelApp\perfil\Controllers\PerfilController;
use PainelApp\download\Controllers\DownloadController;

Route
    ::middleware(
        classe: AuthMiddleware::class,
        action: 'deslogado',
    )

    // LOGIN
    ::controller(LoginController::class)
    ::nome('login')
    ::grupo(function () {
        Route
            ::nome('index')
            ::_rotaNaoUnica()
            ::view('/login');

        Route
            ::nome('login')
            ::_rotaNaoUnica()
            ::request(['hash_validacao_captcha', 'login', 'senha'])
            ::post('/login');
        Route
            ::nome('relogar')
            ::_rotaNaoUnica()
            ::request(['hash_validacao_captcha', 'login', 'senha'])
            ::post('/login/relogar');

        Route
            ::nome('social')
            ::_rotaNaoUnica()
            ::request(['hash_validacao', 'id', 'token', 'code', 'rede'])
            ::post('/login/social');

        Route
            ::nome('desbloquear')
            ::_rotaNaoUnica()
            ::request(['hash_validacao', 'login', 'senha', 'logado'])
            ::post('/login/desbloquear');
    });

Route

    // MANDA PRA LOGIN SE ESTIVER DESLOGADO
    ::middleware(
        classe: App\Middlewares\Painel\AuthMiddleware::class,
        action: 'logado',
    )

    // LOGIN
    ::controller(LoginController::class)
    ::nome('login')
    ::grupo(function () {
        Route
            ::_rotaNaoUnica()
            ::action('bloquear')
            ::view('/bloquear');

        Route
            ::_rotaNaoUnica()
            ::nome('sair')
            ::view('/sair');
    }, true)

    // PERFIL
    ::nome('perfil')
    ::controller(PerfilController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::_rotaNaoUnica()
            ::view('/perfil');

        Route
            ::nome('dado')
            ::_rotaNaoUnica()
            ::view('/perfil/dado');

        Route
            ::nome('atualizar_dado')
            ::_rotaNaoUnica()
            ::action('dado')
            ::request([
                'nome', 'data_nascimento', 'genero', 'email_pessoal', 'telefone_trabalho',
                'telefone_pessoal', 'perfil'
            ])
            ::post('/perfil/dado');

        Route
            ::nome('validarSenha')
            ::_rotaNaoUnica()
            ::request(['senha'])
            ::post('/perfil/validar-senha');

        Route
            ::nome('senha')
            ::_rotaNaoUnica()
            ::view('/perfil/senha');

        Route
            ::nome('atualizar_senha')
            ::_rotaNaoUnica()
            ::action('senha')
            ::request(['hash_validacao', 'senha_atual', 'senha_nova', 'senha_repetir'])
            ::post('/perfil/senha');

        Route
            ::nome('social')
            ::_rotaNaoUnica()
            ::request(['hash_validacao', 'id', 'token', 'rede', 'code', 'acao'])
            ::post('/perfil/social');

        Route
            ::nome('imagem')
            ::_rotaNaoUnica()
            ::request(['hash_validacao'])
            ::request(['arquivo'], 'files')
            ::post('/perfil/imagem');
    }, true)

    // AGENDA
    ::nome('agenda')
    ::controller(AgendaController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::_rotaNaoUnica()
            ::view('/agenda');

        Route
            ::nome('login')
            ::request(['hash_validacao', 'code'])
            ::_rotaNaoUnica()
            ::post('/agenda/login');

        Route
            ::nome('buscar')
            ::_rotaNaoUnica()
            ::request(['data_inicial', 'data_final'])
            ::post('/agenda/buscar');

        Route
            ::nome('salvar')
            ::_rotaNaoUnica()
            ::request([
                'titulo', 'data_inicial', 'data_final', 'hora_inicial', 'hora_final',
                'descricao', 'local', 'video', 'convidado'
            ])
            ::post('/agenda/salvar');

        Route
            ::nome('editar')
            ::_rotaNaoUnica()
            ::request([
                'id', 'titulo', 'data_inicial', 'data_final', 'hora_inicial', 'hora_final',
                'descricao', 'local', 'video', 'convidado', 'notificar'
            ])
            ::post('/agenda/editar');

        Route
            ::nome('confirmar')
            ::_rotaNaoUnica()
            ::request(['id', 'confirmar'])
            ::post('/agenda/confirmar');

        Route
            ::nome('deletar')
            ::_rotaNaoUnica()
            ::request(['id'])
            ::post('/agenda/deletar');
    }, true)

    // APP
    ::controller(AppController::class)
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

        Route
            ::action('redirecionar')
            ::request(['url'])
            ::view('/app/redirecionar');
    }, true)

    // UPLOAD
    ::controller(UploadController::class)
    ::grupo(function () {
        Route
            ::action('extensao')
            ::_rotaNaoUnica()
            ::request(['grupo'])
            ::post('/upload/extensao');
        Route
            ::action('estruturaDiretorio')
            ::_rotaNaoUnica()
            ::request(['grupo'])
            ::post('/upload/estrutura-diretorio');
        Route
            ::action('criarDiretorio')
            ::_rotaNaoUnica()
            ::request(['grupo_inicial', 'grupo_atual', 'nome'])
            ::post('/upload/criar-diretorio');
        Route
            ::action('renomearDiretorio')
            ::_rotaNaoUnica()
            ::request(['grupo_inicial', 'grupo_atual', 'nome'])
            ::post('/upload/renomear-diretorio');
        Route
            ::action('deletarDiretorio')
            ::_rotaNaoUnica()
            ::request(['grupo_inicial', 'grupo_atual'])
            ::post('/upload/deletar-diretorio');

        Route
            ::action('buscar')
            ::_rotaNaoUnica()
            ::request(['pagina', 'pesquisa', 'grupo_inicial', 'grupo_atual'])
            ::post('/upload/buscar');

        Route
            ::action('salvar')
            ::_rotaNaoUnica()
            ::request(['grupo_inicial', 'grupo_atual'])
            ::request(['arquivo'], 'files')
            ::post('/upload/salvar');

        Route
            ::action('mover')
            ::_rotaNaoUnica()
            ::request(['id', 'nome', 'grupo_inicial', 'grupo_atual', 'grupo_destino'])
            ::post('/upload/mover');

        Route
            ::action('renomear')
            ::_rotaNaoUnica()
            ::request(['grupo_atual', 'grupo_inicial', 'id', 'nome'])
            ::post('/upload/renomear');

        Route
            ::action('editar')
            ::_rotaNaoUnica()
            ::request(['id', 'tipo', 'nome', 'largura', 'altura', 'corte_largura', 'corte_altura', 'x', 'y'])
            ::post('/upload/editar');

        Route
            ::action('deletar')
            ::_rotaNaoUnica()
            ::request(['grupo_atual', 'grupo_inicial', 'id'])
            ::post('/upload/deletar');
    }, true)

    // HISTORICO
    ::controller(HistoricoController::class)
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::_rotaNaoUnica()
            ::request(['app', 'relacionado', 'mensagem', '!titulo', '!link', '!notificar'])
            ::post('/historico');
        Route
            ::nome('listar')
            ::_rotaNaoUnica()
            ::request(['pagina', 'app', 'relacionado', '!pesquisa', '!data_de', '!data_ate'])
            ::get('/historico');
        Route
            ::nome('deletar')
            ::_rotaNaoUnica()
            ::delete('/historico/{id}');
    }, true)

    // DOWNLOAD PRIVADO
    ::controller(DownloadController::class)
    ::grupo(function () {
        Route
            ::nome('buscar')
            ::_rotaNaoUnica()
            ::view('/download-privado/{id}');
        Route
            ::nome('download')
            ::_rotaNaoUnica()
            ::view('/download-privado/download/{id}');
        Route
            ::nome('validar')
            ::_rotaNaoUnica()
            ::request(['senha'])
            ::post('/download-privado/{id}');
    }, true)

    // NOTIFICACAO
    ::controller(PainelController\NotificacaoController::class)
    ::grupo(function () {
        Route
            ::nome('listar')
            ::_rotaNaoUnica()
            ::request(['pagina'])
            ::get('/notificacao');
        Route
            ::nome('atualizar')
            ::_rotaNaoUnica()
            ::request(['id'])
            ::post('/notificacao/atualizar-visualizadas');
        Route
            ::nome('abrir')
            ::_rotaNaoUnica()
            ::view('/notificacao/{id}');
        Route
            ::nome('visualizarTodas')
            ::_rotaNaoUnica()
            ::get('/notificacao/visualizar-todas');
    });
