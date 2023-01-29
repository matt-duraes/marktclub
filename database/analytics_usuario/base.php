<?php

return (new \DataBase\DataBase())
    ->id()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_usuario_cliente')
    ->bigint('usuario_cpf')->null()
    ->varchar('usuario_nome')->tamanho(100)->null()
    ->int('quantidade')->tamanho(9)
    ->date('data_acesso');
