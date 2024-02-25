<?php

if (env('DB_STATUS', '') != 'localhost' || env('APP_TIPO', '') != 'localhost') {
    mensagemStatus(403);
    exit();
} elseif (array_key_exists('acao', $_POST) && $_POST['acao'] == 'login') {
    include 'Models/Login.php';
} elseif (!sessaoExiste('DATABASE_LOGIN')) {
    $erro = '';
    if (sessaoExiste('DATABASE_ERRO')) {
        $erro = sessao('DATABASE_ERRO');
        sessaoDeletar('DATABASE_ERRO');
    }
    include 'Views/login.php';
} elseif (array_key_exists('acao', $_POST) && $_POST['acao'] == 'criar') {
    include 'Models/Criar.php';
} elseif (array_key_exists('acao', $_POST) && $_POST['acao'] == 'define') {
    include 'Models/Define.php';
} elseif (array_key_exists('acao', $_POST) && $_POST['acao'] == 'replace') {
    include 'Models/Replace.php';
} elseif (array_key_exists('acao', $_POST) && $_POST['acao'] == 'relacionar') {
    include 'Models/Relacionar.php';
} else {
    $lista = include 'Models/Listar.php';
    include 'Views/index.php';
}
