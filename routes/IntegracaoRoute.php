<?php

use Route\Route;

Route
    ::nome('index')
    ::controller(App\Controllers\Integracao\IndexController::class)
    ::grupo(function () {
        Route
            ::nome('direto')
            ::view('/direto/{parceiro}/{hash}');
});
