<?php

return (new \DataBase\DataBase())
    ->id()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_admin_subempresa')->tamanho(9)->null()
    ->int('quantidade_total')->tamanho(9)
    ->int('quantidade_unico')->tamanho(9)
    ->date('data_acesso');
