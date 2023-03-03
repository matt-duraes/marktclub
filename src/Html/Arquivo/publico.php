<?php

use Http\Response;

$explode = explode('?', $requestUri);
$nome = arquivoPublicoNome($explode[0]);

if (empty($nome)) {
    mensagemStatus(404, localhost: 'O arquivo está sem nome.');
}

$path = DIRETORIO_PUBLICO . '/' . preg_replace('/^\//', '', $nome);
$download = array_key_exists('download', $_GET) && $_GET['download'] == 'sim';

if (!file_exists($path)) {
    mensagemStatus(404, localhost: 'O arquivo não existe.');
} else if (!is_file($path)) {
    mensagemStatus(404, localhost: 'O arquivo não é um arquivo comum.');
} else if (!$download) {
    $Response = new Response(arquivo: $path);
    $Response->render();
} else if ($download) {
    $Response = new Response(download: $path);
    $Response->render();
}
