<?php

use Route\Route;

Route::grupo(function () {
    Route
        ::controller(App\Controllers\Site\ExemploController::class)
        ::view('/exemplo');
});
