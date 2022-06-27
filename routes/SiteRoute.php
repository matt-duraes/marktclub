<?php

use Route\Route;

Route
    ::nome('geral')
    ::grupo(function () {
        Route
            ::nome('index')
            ::controller(App\Controllers\Site\SiteController::class)
            ::view('/');

        Route
            ::nome('fazemos')
            ::controller(App\Controllers\Site\SiteController::class)
            ::view('/o-que-fazemos');

        Route
            ::nome('quemsomos')
            ::controller(App\Controllers\Site\SiteController::class)
            ::action('quemSomos')
            ::view('/quem-somos');

        Route
            ::nome('midia')
            ::controller(App\Controllers\Site\SiteController::class)
            ::view('/midia');

        Route
            ::nome('dicas')
            ::controller(App\Controllers\Site\SiteController::class)
            ::view('/dicas');

        Route
            ::nome('sistema')
            ::controller(App\Controllers\Site\SiteController::class)
            ::view('/o-sistema');

        Route
            ::nome('contratar')
            ::controller(App\Controllers\Site\SiteController::class)
            ::view('/contratar');

        Route
            ::nome('contratar')
            ::controller(App\Controllers\Site\SiteController::class)
            ::request([
                'nome', 'cpf', 'email', 'telefone', 'razao_social', 'nome_fantasia', 'cnpj', 'endereco_cep',
                'endereco_logradouro', 'endereco_numero', 'endereco_complemento', 'endereco_referencia',
                'endereco_bairro', 'endereco_cidade', 'endereco_estado', 'hash_validacao'
            ])
            ::post('/contratar');
    });
