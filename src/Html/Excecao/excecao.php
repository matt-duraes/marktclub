<?php

$titulo = $dado['titulo'] ?? '';
$mensagem = $dado['mensagem'] ?? '';
$codigo = $dado['codigo'] ?? '';

$erroArquivo = '';
$erroLinha = '';
$erroTrace = [];

if (SISTEMA == 'LOCALHOST') {
    $erroArquivo = $dado['erroArquivo'] ?? '';
    $erroLinha = $dado['erroLinha'] ?? '';
    $erroTrace = array_key_exists('erroTrace', $dado) ? explode(PHP_EOL, $dado['erroTrace']) : [];
}

include 'html.php';
