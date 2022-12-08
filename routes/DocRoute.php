<?php

use Route\Route;

Route
    ::nome('index')
    ::controller(App\Controllers\Doc\IndexController::class)
    ::grupo(function () {
        Route::nome('index')::view('/');
    });
