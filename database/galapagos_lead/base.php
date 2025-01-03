<?php

use Database\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id', DataBase::CASCADE, DataBase::CASCADE)
    ->nome('nome')
    ->email('email')
    ->telefone('telefone')
    ->datetime('data_termo')
    ->status()->tamanho(1);
