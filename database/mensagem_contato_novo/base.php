<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->varchar('nome')->tamanho(100)
    ->email('email')
    ->telefone('telefone')
    ->text('mensagem')->null()
    ->text('descoberta_site')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
