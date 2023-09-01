<?php

return (new \Database\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->null()->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->int('id_usuario_cliente')->null()->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->int('origem')->tamanho(1)
    ->varchar('nome')->tamanho(100)
    ->telefone('telefone')->null()
    ->email('email')->null()
    ->text('mensagem')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
