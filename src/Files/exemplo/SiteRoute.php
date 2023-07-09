<?php

use Route\Route;

Route::nome('exemplo')::grupo(function () {
    Route::nome('index')::controller(App\Controllers\Site\ExemploController::class)::view('/');
});
