<?php

use Http\Response;

$explode = explode('?', $requestUri);
if (!array_key_exists(0, $explode) || !is_string($explode[0]) || empty($explode[0])) {
    exit();
}
$requestRealArquivo = preg_replace('/\.[a-zA-Z]{3,4}$/', '', $explode[0]);
$nome = arquivoPublicoNome($requestRealArquivo);

if (empty($nome)) {
    mensagemStatus(404, localhost: 'O arquivo está sem nome.');
}

$download = array_key_exists('download', $_GET) && $_GET['download'] == 'sim';

$path = DIRETORIO_PUBLICO . '/' . preg_replace('/^\//', '', $nome);
require_once 'validar_path.php';

include 'imagem.php';

if (!$download) {
    $Response = new Response(arquivo: $path);
    $Response->render();
} elseif ($download) {
    $Response = new Response(download: $path);
    $Response->render();
}
