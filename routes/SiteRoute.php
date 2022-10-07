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
            ::view('/convenio');
        Route
            ::nome('proxima')
            ::view('/convenios/mapa');
    });
