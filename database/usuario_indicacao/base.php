<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->cod()
    ->int('id_admin_empresa')->tamanho(9)->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->int('id_usuario_cliente')->tamanho(9)->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->int('vinculo')->tamanho(9)->relacionado(TABELA_USUARIO_CLIENTE, 'id')->null()
    ->char('hash')->tamanho(36)
    ->nome('nome')
    ->email('email')
    ->telefone('telefone')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
