<?php

use Route\Route;

Route::nome('login')::controller(App\Controllers\Login\IndexController::class)::grupo(function () {
    Route::nome('index')::view('/login');
    Route::nome('logar')::request(['login', 'senha'])::post('/login');
});
