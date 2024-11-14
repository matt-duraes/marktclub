<?php

use Http\Response;

$explode = explode('?', $requestUri);
if (!array_key_exists(0, $explode) || !is_string($explode[0]) || empty($explode[0])) {
    exit();
}
$requestRealArquivo = preg_replace('/\.[a-zAZ]{3,4}$/', '', $explode[0]);
$nome = arquivoPublicoNome($requestRealArquivo);

if (empty($nome)) {
    mensagemStatus(404, localhost: 'O arquivo está sem nome.');
}

$path = DIRETORIO_PUBLICO . '/' . preg_replace('/^\//', '', $nome);
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
