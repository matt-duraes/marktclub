<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')->null()
    ->varchar('local')->tamanho(100)
    ->varchar('tipo')->tamanho(100)
    ->nome('nome')
    ->email('email')
    ->telefone('telefone')
    ->longtext('mensagem')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
