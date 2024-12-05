<?php

$diretorio = $_POST['diretorio'] ?? '';
$status = $_POST['status'] ?? '';

if (!preg_match('/^[a-zA-Z]+$/', $diretorio) || !preg_match('/^([4-5]{1}[0-9]{2})|excecao$/', $status)) {
    http_response_code(500);
    exit();
}

$path = ROOT . '/files/build/views/status_' . $diretorio . '_' . $status . '.php';
if (!file_exists($path)) {
    http_response_code(500);
    exit();
}

ob_start();
require_once $path;
$html = ob_get_clean();

http_response_code(201);
echo $html;
