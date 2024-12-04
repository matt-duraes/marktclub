<?php

include ROOT . '/resources/php/site/tema.php';
$Logado = new App\Middlewares\Site\AuthMiddleware();
try {
    $logado = $Logado->logadoInterno() === true;
} catch (\Throwable $th) {
    $logado = false;
}
try {
    $Clube = new App\Middlewares\Site\ClubeMiddleware();
    $define = $Clube->buscar();
} catch (\Throwable $e) {
    $define = false;
}

$defineLista = [
    'CLUBE_FAVICON'         => '',
    'CLUBE_TITULO'          => 'Erro!',
    'CLUBE_LOGO_CLASSE'     => '',
    'CLUBE_LOGO_PRINCIPAL'  => '',
    'CLUBE_LOGO_SECUNDARIA' => '',
    'CLUBE_COR_PRINCIPAL'   => '#169e91',
    'CLUBE_COR_SECUNDARIA'  => '#F6F6F6',
    'LINK'                  => '/',
    'LINK_LOGIN'            => '/login#login',
    'LINK_ATIVACAO'         => '/login#ativar',
    'LINK_RECUPERAR_SENHA'  => '/login#senha',
    'LOGIN_STATUS'          => true,
    'CHAT'                  => false
];

if (!$define) {
    foreach ($defineLista as $ind => $val) {
        if (defined($ind)) {
            continue;
        }
        define($ind, $val);
    }
}
