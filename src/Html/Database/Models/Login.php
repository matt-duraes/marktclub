<?php

$erro = '';
if (chaveExiste(['login', 'senha'], $_POST)) {
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    if (!empty($login) && $login == env('DB_USUARIO') && !empty($senha) && $senha == env('DB_SENHA')) {
        sessao('DATABASE_LOGIN', true);
        sessaoDeletar('DATABASE_ERRO', '');
    } else {
        sessao('DATABASE_ERRO', 'Login e/ou senha inválida.');
    }
}

header('location: ' . LINK . '/__base');
