<?php

use Http\Response;

$hash = explode('.', explode('?', $requestUri)[0])[1];
$nome = (new \Helpers\CryptHelper())->decode($hash);

if (empty($nome)) {
    mensagemStatus(404, 'O arquivo está sem nome.');
}

$path = DIRETORIO_PUBLICO . '/' . preg_replace('/^\//', '', $nome);
$download = array_key_exists('download', $_GET) && $_GET['download'] == 1;

if (!file_exists($path)) {
    mensagemStatus(404, 'O arquivo não existe.');
} else if (!is_file($path)) {
    mensagemStatus(404, 'O arquivo não é um arquivo comum.');
} else if (!$download) {
    $Response = new Response(arquivo: $path);
    $Response->render();
} else if ($download) {
    $Response = new Response(download: $path);
    $Response->render();
}
