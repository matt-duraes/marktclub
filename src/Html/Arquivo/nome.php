<?php

use Http\Response;

$explode = explode('?', $requestUri);
$nome = arquivoPublicoNome($explode[0]);
if (empty($nome)) {
    mensagemStatus(404, localhost: 'O arquivo está sem nome.');
}

$path = DIRETORIO_PRIVADO . '/' . preg_replace('/^\//', '', $nome);
if (!file_exists($path)) {
    mensagemStatus(404, localhost: 'O arquivo não existe.');
} elseif (!is_file($path)) {
    mensagemStatus(404, localhost: 'O arquivo não é um arquivo comum.');
}

$download = array_key_exists('download', $_GET) && $_GET['download'] == 'sim';

include 'imagem.php';

if (!$download) {
    $Response = new Response(arquivo: $path);
    $Response->render();
} elseif ($download) {
    $Response = new Response(download: $path);
    $Response->render();
}
