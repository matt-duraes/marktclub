<?php

use Route\Route;
use App\Middlewares\Api\TokenMiddleware;

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
            ::nome('salvar')
            ::request(['grupo'])
            ::request(['arquivo'], 'files')
            ::post('/upload-arquivo');
        Route
            ::nome('atualizar')
            ::request(['grupo'])
            ::put('/upload-arquivo');
    });
