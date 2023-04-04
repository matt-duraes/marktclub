<?php

use Route\Route;

Route
    ::nome('index')
    ::controller(App\Controllers\Site\IndexController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/');
    });

Route
    ::nome('loja')
    ::controller(App\Controllers\Site\LojaController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/convenios');

        Route
            ::nome('busca')
            ::request(['!estado', '!categoria', '!tag', '!estabelecimento', '!pesquisa', '!ordem'])
            ::view('/convenios/buscar/{!pesquisa}');

        Route
            ::nome('detalhe')
            ::view('/convenios/{url}');
        Route
            ::nome('proxima')
            ::view('/convenios/mapa');
    });
