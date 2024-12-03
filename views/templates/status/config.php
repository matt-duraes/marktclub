<?php

include ROOT . '/resources/php/site/tema.php';

$Logado = new App\Middlewares\Site\AuthMiddleware();
try {
    $logado = $Logado->logado() === true;
} catch (\Throwable $th) {
    $logado = false;
}

(new App\Middlewares\Site\ClubeMiddleware())->buscar();
