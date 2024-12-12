<?php

use Http\Response;

$explode = explode('?', $requestUri);
$nome = arquivoPublicoNome($explode[0]);
if (empty($nome)) {
    mensagemStatus(404, localhost: 'O arquivo está sem nome.');
}

$download = array_key_exists('download', $_GET) && $_GET['download'] == 'sim';
$path = DIRETORIO_PRIVADO . '/' . preg_replace('/^\//', '', $nome);
require_once 'validar_path.php';
require_once 'imagem.php';

if (!$download) {
    $Response = new Response(arquivo: $path);
    $Response->render();
} elseif ($download) {
    $Response = new Response(download: $path);
    $Response->render();
}
