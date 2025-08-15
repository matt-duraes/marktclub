<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->int('id_usuario_cliente')->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->cpf()
    ->text('voucher')
    ->datetime('data_vencimento')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
