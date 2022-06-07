<?php

if (isset($_POST['login']) && isset($_POST['senha'])) {
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    if (!empty($login) && $login == env('DB_USUARIO') && !empty($senha) && $senha == env('DB_SENHA')) {
        sessao('DATABASE_LOGIN', true);
        header('location: ' . LINK . '/__base');
    } else {
        $erro = 'Login ou senha incorretos.';
    }
}
