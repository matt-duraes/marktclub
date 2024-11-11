<?php

require_once __DIR__ . '/../vendor/autoload.php';

new \System\Config\FunctionLoading();
new \System\Config\EnvConfig();
new \System\Config\Constantes();
new \System\Config\Link();

require_once __DIR__ . '/../src/Config/Ini.php';
(new \System\Config\Session())->start();

require_once __DIR__ . '/../src/Config/Error.php';
require_once __DIR__ . '/../src/Config/Diretorio.php';

if (file_exists(__DIR__ . '/../database/tabela.php')) {
    require_once __DIR__ . '/../database/tabela.php';
}

$__cacheVida = env('CACHE_VIDA', '');
if (!empty($__cacheVida)) {
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $__cacheVida) . ' GMT');
    header('Cache-Control: max-age=' . $__cacheVida);
    header('Pragma: cache');
}
header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
header('X-Frame-Options: DENY');

$__securityPolicy = env('SECURITY_POLICY', '');
if (!empty($__securityPolicy)) {
    header('Content-Security-Policy: ' . $__securityPolicy);
    header('X-Content-Security-Policy: ' . $__securityPolicy);
}
unset($__securityPolicy);

$__permissionPolicy = env('PERMISSION_POLICY', '');
if (!empty($__permissionPolicy)) {
    header('Permissions-Policy: ' . $__permissionPolicy);
}
unset($__permissionPolicy);

header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');
header('X-XSS-Protection: 1; mode=block');
/*
|--------------------------------------------------------------------------
| USUÁRIO E SENHA
|--------------------------------------------------------------------------
|
| Caso tenha Usuário e Senha no env, o usuário tem que fazer o login
| para ter acesso ao sistema
|
|--------------------------------------------------------------------------
*/
if (!empty(env('APP_LOGIN', '')) && !empty(env('APP_SENHA', '')) && !cookieExiste('APP_LOGADO')) {
    require_once __DIR__ . '/../src/Html/Login/index.php';
    exit();
}
/*
|--------------------------------------------------------------------------
| AUTOLOAD
|--------------------------------------------------------------------------
|
| Autoload específico do sistema
|
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/../src/Config/Autoload.php';

/*
|--------------------------------------------------------------------------
| ROTAS PADRÕES
|--------------------------------------------------------------------------
|
| Rotas que são usadas internamente pelo framework
|
*/
$requestUri = array_key_exists('REQUEST_URI', $_SERVER) ? explode('/', $_SERVER['REQUEST_URI']) : [];
$requestUri = array_key_exists(1, $requestUri) ? $requestUri[1] : '';

if ($requestUri == '__endereco-cep' && $_SERVER['REQUEST_METHOD'] ?? '' == 'POST') {
    require_once __DIR__ . '/../src/Html/Endereco/cep.php';
    exit();
} elseif ($requestUri == '__endereco-cidade' && $_SERVER['REQUEST_METHOD'] ?? '' == 'POST') {
    require_once __DIR__ . '/../src/Html/Endereco/cidade.php';
    exit();
} elseif ($requestUri == '__postman' && SISTEMA == 'LOCALHOST') {
    require_once __DIR__ . '/../src/Html/Postman/index.php';
    exit();
} elseif ($requestUri == '__base' && SISTEMA == 'LOCALHOST') {
    require_once __DIR__ . '/../src/Html/Database/index.php';
    exit();
} elseif ($requestUri == '__tests' && SISTEMA == 'LOCALHOST') {
    require_once __DIR__ . '/../src/Html/Tests/index.php';
    exit();
} elseif ($requestUri == '__documentacao' && SISTEMA != 'PRODUCAO') {
    require_once __DIR__ . '/../src/Html/Documentacao/index.php';
    exit();
} elseif ($requestUri == '__enviar-email-sistema' && METODO == 'POST') {
    require_once __DIR__ . '/../src/Html/Email/enviarEmail.php';
    exit();
} elseif ($requestUri == '__random-encode' && METODO == 'POST' && SISTEMA == 'LOCALHOST') {
    require_once __DIR__ . '/../src/Html/RandomEncode/index.php';
    exit();
} elseif (str_starts_with($requestUri, 'aqioulc.') && METODO == 'GET') {
    require_once __DIR__ . '/../src/Html/Arquivo/publico.php';
    exit();
} elseif (str_starts_with($requestUri, 'aqiorvd.') && METODO == 'GET') {
    require_once __DIR__ . '/../src/Html/Arquivo/privado.php';
    exit();
} elseif (str_starts_with($requestUri, 'aqiornm.') && METODO == 'GET') {
    require_once __DIR__ . '/../src/Html/Arquivo/nome.php';
    exit();
}
unset($requestUri);

/*
|--------------------------------------------------------------------------
| APP START
|--------------------------------------------------------------------------
|
| Roda o APP para utilização do fw
|
|--------------------------------------------------------------------------
*/
$App = (new \System\Config\App())->run();
if (method_exists($App, 'render')) {
    $App->render();
} else {
    throw new \Erro\Excecao(status: 404);
}
