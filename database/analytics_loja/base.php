<?php

return (new \DataBase\DataBase())
    ->id()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_parceiro_loja')->tamanho(9)->null()
    ->int('parceiro_estabelecimento')->tamanho(1)->null()
    ->varchar('parceiro_nome')->tamanho(100)->null()
    ->int('quantidade')->tamanho(9)
    ->date('data_acesso');
