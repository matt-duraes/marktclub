<?php

use Route\Route;
use App\Middlewares\AuthMiddleware;
use PainelController\AppController;
use PainelController\UploadController;
use PainelController\EnderecoController;
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
            ::rotaNaoUnica()
            ::view('/login');

        Route
            ::nome('login')
            ::rotaNaoUnica()
            ::request(['hash_validacao_captcha', 'login', 'senha'])
            ::post('/login');
        Route
            ::nome('relogar')
            ::rotaNaoUnica()
            ::request(['hash_validacao_captcha', 'login', 'senha'])
            ::post('/login/relogar');

        Route
            ::nome('social')
            ::rotaNaoUnica()
            ::request(['hash_validacao', 'id', 'token', 'code', 'rede'])
            ::post('/login/social');

        Route
            ::nome('desbloquear')
            ::rotaNaoUnica()
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
            ::rotaNaoUnica()
            ::action('bloquear')
            ::view('/bloquear');

        Route
            ::rotaNaoUnica()
            ::nome('sair')
            ::view('/sair');
    }, true)

    // PERFIL
    ::nome('perfil')
    ::controller(PerfilController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::rotaNaoUnica()
            ::view('/perfil');

        Route
            ::nome('dado')
            ::rotaNaoUnica()
            ::view('/perfil/dado');

        Route
            ::nome('atualizar_dado')
            ::rotaNaoUnica()
            ::action('dado')
            ::request([
                'nome', 'data_nascimento', 'genero', 'email_pessoal', 'telefone_trabalho',
                'telefone_pessoal', 'perfil'
            ])
            ::post('/perfil/dado');

        Route
            ::nome('validarSenha')
            ::rotaNaoUnica()
            ::request(['senha'])
            ::post('/perfil/validar-senha');

        Route
            ::nome('senha')
            ::rotaNaoUnica()
            ::get('/perfil/senha');

        Route
            ::nome('senha')
            ::rotaNaoUnica()
            ::request(['hash_validacao', 'senha_atual', 'senha_nova', 'senha_repetir'])
            ::post('/perfil/senha');

        Route
            ::nome('social')
            ::rotaNaoUnica()
            ::request(['hash_validacao', 'id', 'token', 'rede', 'code', 'acao'])
            ::post('/perfil/social');

        Route
            ::nome('empresa')
            ::rotaNaoUnica()
            ::view('/perfil/empresa');
        Route
            ::nome('empresa')
            ::rotaNaoUnica()
            ::request(['hash_validacao', 'empresa'])
            ::post('/perfil/empresa');

        Route
            ::nome('imagem')
            ::rotaNaoUnica()
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
            ::rotaNaoUnica()
            ::view('/agenda');

        Route
            ::nome('login')
            ::request(['hash_validacao', 'code'])
            ::rotaNaoUnica()
            ::post('/agenda/login');

        Route
            ::nome('buscar')
            ::rotaNaoUnica()
            ::request(['data_inicial', 'data_final'])
            ::post('/agenda/buscar');

        Route
            ::nome('salvar')
            ::rotaNaoUnica()
            ::request([
                'titulo', 'data_inicial', 'data_final', 'hora_inicial', 'hora_final',
                'descricao', 'local', 'video', 'convidado'
            ])
            ::post('/agenda/salvar');

        Route
            ::nome('editar')
            ::rotaNaoUnica()
            ::request([
                'id', 'titulo', 'data_inicial', 'data_final', 'hora_inicial', 'hora_final',
                'descricao', 'local', 'video', 'convidado', 'notificar'
            ])
            ::post('/agenda/editar');

        Route
            ::nome('confirmar')
            ::rotaNaoUnica()
            ::request(['id', 'confirmar'])
            ::post('/agenda/confirmar');

        Route
            ::nome('deletar')
            ::rotaNaoUnica()
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
            ::rotaNaoUnica()
            ::request(['grupo'])
            ::post('/upload/extensao');
        Route
            ::action('estruturaDiretorio')
            ::rotaNaoUnica()
            ::request(['grupo'])
            ::post('/upload/estrutura-diretorio');
        Route
            ::action('criarDiretorio')
            ::rotaNaoUnica()
            ::request(['grupo_inicial', 'grupo_atual', 'nome'])
            ::post('/upload/criar-diretorio');
        Route
            ::action('renomearDiretorio')
            ::rotaNaoUnica()
            ::request(['grupo_inicial', 'grupo_atual', 'nome'])
            ::post('/upload/renomear-diretorio');
        Route
            ::action('deletarDiretorio')
            ::rotaNaoUnica()
            ::request(['grupo_inicial', 'grupo_atual'])
            ::post('/upload/deletar-diretorio');

        Route
            ::action('buscar')
            ::rotaNaoUnica()
            ::request(['pagina', 'pesquisa', 'grupo_inicial', 'grupo_atual'])
            ::post('/upload/buscar');

        Route
            ::action('salvar')
            ::rotaNaoUnica()
            ::request(['grupo_inicial', 'grupo_atual'])
            ::request(['arquivo'], 'files')
            ::post('/upload/salvar');

        Route
            ::action('mover')
            ::rotaNaoUnica()
            ::request(['id', 'nome', 'grupo_inicial', 'grupo_atual', 'grupo_destino'])
            ::post('/upload/mover');

        Route
            ::action('renomear')
            ::rotaNaoUnica()
            ::request(['grupo_atual', 'grupo_inicial', 'id', 'nome'])
            ::post('/upload/renomear');

        Route
            ::action('editar')
            ::rotaNaoUnica()
            ::request(['id', 'tipo', 'nome', 'largura', 'altura', 'corte_largura', 'corte_altura', 'x', 'y'])
            ::post('/upload/editar');

        Route
            ::action('deletar')
            ::rotaNaoUnica()
            ::request(['grupo_atual', 'grupo_inicial', 'id'])
            ::post('/upload/deletar');
    }, true)

    // HISTORICO
    ::controller(HistoricoController::class)
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::rotaNaoUnica()
            ::request(['app', 'relacionado', 'mensagem', '!titulo', '!link', '!notificar'])
            ::post('/historico');
        Route
            ::nome('listar')
            ::rotaNaoUnica()
            ::request(['pagina', 'app', 'relacionado', '!pesquisa', '!data_de', '!data_ate'])
            ::get('/historico');
        Route
            ::nome('deletar')
            ::rotaNaoUnica()
            ::delete('/historico/{id}');
    }, true)

    // ENDERECO
    ::controller(EnderecoController::class)
    ::grupo(function () {
        Route
            ::nome('buscarGeolocalizacao')
            ::rotaNaoUnica()
            ::request(['pais', '!titulo', '!cep', '!logradouro', '!numero', '!bairro', '!cidade', '!estado'])
            ::post('/sistema-endereco/buscar-geolocalizacao');
        Route
            ::nome('buscarEnderecoPeloCep')
            ::rotaNaoUnica()
            ::request(['cep'])
            ::post('/sistema-endereco/buscar-endereco-pelo-cep');
        Route
            ::nome('buscarCidade')
            ::rotaNaoUnica()
            ::request(['estado'])
            ::post('/sistema-endereco/buscar-cidade');
        Route
            ::nome('listarEndereco')
            ::rotaNaoUnica()
            ::request(['tabela', 'local', 'id', 'pagina', '!quantidade', '!pais', '!estado', '!titulo'])
            ::post('/sistema-endereco/buscar-lista');
        Route
            ::nome('salvarEndereco')
            ::rotaNaoUnica()
            ::request([
                'tabela', 'local', 'titulo', 'telefone', 'pais', 'cep', 'logradouro', 'numero', 'complemento',
                'referencia', 'bairro', 'estado', 'cidade', 'latitude', 'longitude'
            ])
            ::post('/sistema-endereco/salvar-endereco');
        Route
            ::nome('atualizarEndereco')
            ::rotaNaoUnica()
            ::request([
                'titulo', 'telefone', 'pais', 'cep', 'logradouro', 'numero', 'complemento',
                'referencia', 'bairro', 'estado', 'cidade', 'latitude', 'longitude'
            ])
            ::post('/sistema-endereco/atualizar-endereco/{id}');
    }, true)

    // DOWNLOAD PRIVADO
    ::controller(DownloadController::class)
    ::grupo(function () {
        Route
            ::nome('buscar')
            ::rotaNaoUnica()
            ::view('/download-privado/{id}');
        Route
            ::nome('download')
            ::rotaNaoUnica()
            ::view('/download-privado/download/{id}');
        Route
            ::nome('validar')
            ::rotaNaoUnica()
            ::request(['senha'])
            ::post('/download-privado/{id}');
    }, true)

    // NOTIFICACAO
    ::controller(PainelController\NotificacaoController::class)
    ::grupo(function () {
        Route
            ::nome('listar')
            ::rotaNaoUnica()
            ::request(['pagina'])
            ::get('/notificacao');
        Route
            ::nome('atualizar')
            ::rotaNaoUnica()
            ::request(['id'])
            ::post('/notificacao/atualizar-visualizadas');
        Route
            ::nome('abrir')
            ::rotaNaoUnica()
            ::view('/notificacao/{id}');
        Route
            ::nome('visualizarTodas')
            ::rotaNaoUnica()
            ::get('/notificacao/visualizar-todas');
    });
