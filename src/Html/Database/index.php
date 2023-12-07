<?php

if (array_key_exists('acao', $_POST) && $_POST['acao'] == 'criar' && array_key_exists('tabela', $_POST) && $_POST['tabela']) {
    include 'php/criar.php';
} else {
    $lista = include 'php/lista.php';
    include 'php/html.php';
}
