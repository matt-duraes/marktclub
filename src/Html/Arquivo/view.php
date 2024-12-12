<?php

use Http\Response;

$pathRequest = array_key_exists('REQUEST_URI', $_SERVER) ? trim(preg_replace('/^\//', '', $_SERVER['REQUEST_URI'])) : '';
if (empty($pathRequest)) {
    exit();
}

$explode = explode('?', $pathRequest);
if (!array_key_exists(0, $explode) || !is_string($explode[0]) || empty($explode[0])) {
    exit();
}

$download = array_key_exists('download', $_GET) && $_GET['download'] == 'sim';
$path = ROOT . '/views/' . $explode[0];
$ext = arquivoExt($path);

if(!in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'avif']) || !file_exists($path) || !is_file($path)) {
    mensagemStatus(404, localhost: 'Arquivo não é imagem, não existe ou não é um arquivo.');
}

include 'imagem.php';

if (!file_exists($path)) {
    mensagemStatus(404, localhost: 'Esse arquivo não existe.');
} elseif (!$download) {
    $Response = new Response(arquivo: $path);
    $Response->render();
    exit();
} elseif ($download) {
    $Response = new Response(download: $path);
    $Response->render();
    exit();
}
mensagemStatus(404, localhost: 'Passou por tudo e falhou.');
