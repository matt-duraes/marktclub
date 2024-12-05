<?php

require_once __DIR__ . '/../../vendor/autoload.php';

new \System\Config\FunctionLoading();
new \System\Config\EnvConfig();
new \System\Config\Constantes();
new \System\Config\Link();

require_once __DIR__ . '/../Config/Ini.php';
(new \System\Config\Session())->start();

if (!array_key_exists('FW_ERRO_STATUS', $_POST) || 'nao' !== $_POST['FW_ERRO_STATUS']) {
    require_once __DIR__ . '/../Config/Error.php';
}

require_once __DIR__ . '/../Config/Diretorio.php';

if (file_exists(__DIR__ . '/../../database/tabela.php')) {
    require_once __DIR__ . '/../../database/tabela.php';
}

$__cacheVida = env('CACHE_VIDA', '');
if (!empty($__cacheVida)) {
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $__cacheVida) . ' GMT');
    header('Cache-Control: max-age=' . $__cacheVida);
    header('Pragma: cache');
}
header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
header('X-Frame-Options: DENY');

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
    require_once __DIR__ . '/../Html/Login/index.php';
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
require_once __DIR__ . '/../Config/Autoload.php';

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

if ($requestUri == '__endereco-cep' && METODO == 'POST') {
    require_once __DIR__ . '/../Html/Endereco/cep.php';
    exit();
} elseif ($requestUri == '__endereco-cidade' && METODO == 'POST') {
    require_once __DIR__ . '/../Html/Endereco/cidade.php';
    exit();
} elseif ($requestUri == '__postman' && SISTEMA == 'LOCALHOST') {
    require_once __DIR__ . '/../Html/Postman/index.php';
    exit();
} elseif ($requestUri == '__base' && SISTEMA == 'LOCALHOST') {
    require_once __DIR__ . '/../Html/Database/index.php';
    exit();
} elseif ($requestUri == '__tests' && SISTEMA == 'LOCALHOST') {
    require_once __DIR__ . '/../Html/Tests/index.php';
    exit();
} elseif ($requestUri == '__documentacao' && SISTEMA != 'PRODUCAO') {
    require_once __DIR__ . '/../Html/Documentacao/index.php';
    exit();
} elseif ($requestUri == '__enviar-email-sistema' && METODO == 'POST') {
    require_once __DIR__ . '/../Html/Email/enviarEmail.php';
    exit();
} elseif ($requestUri == '__random-encode' && METODO == 'POST' && SISTEMA == 'LOCALHOST') {
    require_once __DIR__ . '/../Html/RandomEncode/index.php';
    exit();
} elseif (str_starts_with($requestUri, 'aqioulc.') && METODO == 'GET') {
    require_once __DIR__ . '/../Html/Arquivo/publico.php';
    exit();
} elseif (str_starts_with($requestUri, 'aqiorvd.') && METODO == 'GET') {
    require_once __DIR__ . '/../Html/Arquivo/privado.php';
    exit();
} elseif (str_starts_with($requestUri, 'aqiornm.') && METODO == 'GET') {
    require_once __DIR__ . '/../Html/Arquivo/nome.php';
    exit();
} elseif ($requestUri == 'images' && METODO == 'GET') {
    require_once __DIR__ . '/../Html/Arquivo/view.php';
    exit();
} elseif ($requestUri == 'fw-erro-status' && METODO == 'POST') {
    require_once __DIR__ . '/../Html/Erro/projeto.php';
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
