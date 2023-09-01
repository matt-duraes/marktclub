<?php

use Route\Route;
use App\Middlewares\Api\TokenMiddleware;

Route
    ::nome('painel_historico')
    ::middleware(TokenMiddleware::class, 'token')
    ::controller(ApiController\PainelHistoricoController::class)
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::request([
                'relacionado', 'app', 'acao', '!dado', '!mensagem', '!notificar_link',
                '!notificar_equipe', '!notificar_titulo'
            ])
                ::post('/painel-historico');

        Route
            ::nome('listar')
            ::request([
                'pagina', 'app', 'relacionado', '!data_de', '!data_ate', '!pesquisa'
            ], 'json')
                ::get('/painel-historico');

        Route
            ::nome('atualizar')
            ::request(['mensagem'])
            ::put('/painel-historico/{id}');

        Route
            ::nome('deletar')
            ::delete('/painel-historico/{id}');
    });

Route
    ::nome('upload_grupo')
    ::middleware(TokenMiddleware::class, 'token')
    ::controller(\ApiController\UploadGrupoController::class)
    ::grupo(function () {
        Route
            ::nome('buscar')
            ::get('/upload-grupo/{id}');
        Route
            ::nome('validar')
            ::request(['raiz', 'grupo'], 'json')
            ::get('/upload-grupo/validar');
        Route
            ::nome('salvar')
            ::request(['grupo', 'nome'])
            ::post('/upload-grupo');
        Route
            ::nome('atualizar')
            ::request(['nome'])
            ::put('/upload-grupo/{id}');
        Route
            ::nome('deletar')
            ::delete('/upload-grupo/{id}');
        Route
            ::nome('pai')
            ::get('/upload-grupo/pai/{id}');
        Route
            ::nome('filho')
            ::get('/upload-grupo/filho/{id}');
    });
Route
    ::nome('upload_arquivo')
    ::middleware(TokenMiddleware::class, 'token')
    ::controller(\ApiController\UploadArquivoController::class)
    ::grupo(function () {
        Route
            ::nome('listar')
            ::request(['pagina', 'pesquisa', 'grupo'], 'json')
            ::get('/upload-arquivo');
        Route
            ::nome('buscar')
            ::get('/upload-arquivo/{id}');
        Route
            ::nome('salvar')
            ::request(['grupo'])
            ::request(['arquivo'], 'files')
            ::post('/upload-arquivo');
        Route
            ::nome('atualizar')
            ::request(['!grupo', '!nome'])
            ::put('/upload-arquivo/{id}');
        Route
            ::nome('deletar')
            ::delete('/upload-arquivo/{id}');
    });

Route
    ::nome('painel_notificacao')
    ::middleware(TokenMiddleware::class, 'token')
    ::controller(\ApiController\PainelNotificacaoController::class)
    ::grupo(function () {
        Route
            ::nome('buscar')
            ::get('/painel-notificacao/{id}');
        Route
            ::nome('listar')
            ::request(['!novo', '!clicado', '!quantidade', '!pagina'], 'json')
            ::get('/painel-notificacao');
        Route
            ::nome('salvar')
            ::request(['titulo', 'mensagem', 'link', 'botao', 'equipe', 'dono'])
            ::post('/painel-notificacao');
        Route
            ::nome('atualizar')
            ::request(['status'])
            ::put('/painel-notificacao/{id}');
        Route
            ::nome('visualizarTodas')
            ::put('/painel-notificacao/visualizar-todas');
    });

Route
    ::nome('log')
    ::controller(\ApiController\LogErroController::class)
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::request(['mensagem', 'codigo', 'arquivo', 'linha', 'trace', 'status'])
            ::post('/log-erro');
        Route
            ::nome('listar')
            ::request(['pagina', '!quantidade'], 'json')
            ::get('/log-erro');
        Route
            ::nome('buscar')
            ::get('/log-erro/{id}');
        Route
            ::nome('atualizar')
            ::request(['status'])
            ::put('/log-erro/{id}');
    });

Route
    ::nome('endereco')
    ::controller(\ApiController\EnderecoController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['endereco:listar'])
            ::request([
                '!pais', '!estado', '!cidade', 'tipo', 'local', 'vinculo', '!ordem'
            ], 'json')
            ::get('/endereco');

        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['endereco:buscar'])
            ::get('/endereco/{id}');

        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['endereco:salvar'])
            ::request([
                'vinculo', 'tipo', 'local', 'titulo', 'telefone', 'cep', 'logradouro', 'complemento',
                'referencia', 'numero', 'bairro', 'cidade', 'estado', 'pais', 'latitude',
                'longitude', 'principal'
            ])
            ::post('/endereco');

        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['endereco:atualizar'])
            ::request([
                '!vinculo', '!tipo', '!local', '!titulo', '!telefone', '!cep', '!logradouro', '!complemento',
                '!referencia', '!numero', '!bairro', '!cidade', '!estado', '!pais', '!latitude',
                '!longitude', '!principal'
            ])
            ::put('/endereco/{id}');

        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['endereco:deletar'])
            ::delete('/endereco/{id}');
    });
