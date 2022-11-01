<?php

return (new \DataBase\DataBase())
    ->id()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_usuario_cliente')
    ->int('id_parceiro_loja')->tamanho(9)->null()
    ->bigint('usuario_cpf')->null()
    ->varchar('usuario_nome')->tamanho(100)->null()
    ->varchar('parceiro_nome')->tamanho(100)
    ->int('quantidade')->tamanho(9)
    ->date('data_acesso');
