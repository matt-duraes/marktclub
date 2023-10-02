<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->nome('nome')->tamanho(100)
    ->email('email')
    ->telefone('telefone')
    ->longtext('mensagem')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
