<?php

return (new \DataBase\DataBase())
    ->id()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_admin_subempresa')->tamanho(9)->null()
    ->int('id_usuario_cliente')
    ->varchar('usuario_cpf')->tamanho(36)->null()
    ->varchar('usuario_nome')->tamanho(100)->null()
    ->int('quantidade')->tamanho(9)
    ->date('data_acesso');
