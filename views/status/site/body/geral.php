<?php

try {
    $Logado = new App\Middlewares\Site\AuthMiddleware();
    $logado = $Logado->logadoInterno() === true ? 'sim' : 'nao';
} catch (\Throwable) {
    $logado = 'nao';
}

return [
    'logado' => $logado
];
