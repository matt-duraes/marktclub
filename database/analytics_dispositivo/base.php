<?php

return (new \DataBase\DataBase())
    ->id()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('quantidade')->tamanho(9)
    ->text('dispositivo')
    ->date('data_acesso');
