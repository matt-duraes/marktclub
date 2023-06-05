<?php

use Route\Route;

Route
    ::nome('loginFenae')
    ::controller(App\Controllers\Oauth\FenaeController::class)
    ::grupo(function () {
        Route
            ::nome('paginaLogin')
            ::view('/login/fenae');
        Route
            ::nome('pegarToken')
            ::view('/login/fenae-autorizar');
    });
