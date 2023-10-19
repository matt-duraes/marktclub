<?php

use Database\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')->null()
    ->int('id_usuario_cliente')->tamanho(9)->relacionado(TABELA_USUARIO_CLIENTE, 'id')->null()
    ->int('origem')->tamanho(1)
    ->varchar('nome')->tamanho(100)
    ->telefone('telefone')->null()
    ->email('email')->null()
    ->text('mensagem')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
