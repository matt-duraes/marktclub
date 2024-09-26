<?php

return (new \DataBase\DataBase())
    ->id()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_admin_subempresa')->tamanho(9)->null()
    ->int('quantidade')->tamanho(9)
    ->text('dispositivo')
    ->date('data_acesso');
