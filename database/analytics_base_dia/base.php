<?php

return (new \DataBase\DataBase())
    ->id()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('status_ativo')->tamanho(9)
    ->int('status_inativo')->tamanho(9)
    ->int('status_bloqueado')->tamanho(9)
    ->date('data_acesso');
