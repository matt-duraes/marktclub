<?php

$post = $_SERVER['REQUEST_METHOD'] == 'POST';
if ($post && array_key_exists('acao', $_POST) && $_POST['acao'] == 'teste') {
    echo include __DIR__ . '/Models/Teste.php';
    exit();
} elseif ($post) {
    exit();
}
include __DIR__ . '/Models/Menu.php';
include __DIR__ . '/Views/index.php';
