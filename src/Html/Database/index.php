<?php

if (array_key_exists('acao', $_POST) && $_POST['acao'] == 'criar') {
    // $path = __DIR__ . '/../../../database/tabela.php';
    // if(!file_exists($path)) {
    //     include 'Models/Define.php';
    // }
    include 'Models/Criar.php';
} elseif (array_key_exists('acao', $_POST) && $_POST['acao'] == 'configurar') {
    include 'Models/Define.php';
} elseif (array_key_exists('acao', $_POST) && $_POST['acao'] == 'relacionar') {
    include 'Models/Relacionar.php';
} else {
    $lista = include 'Models/Listar.php';
    include 'Views/index.php';
}
