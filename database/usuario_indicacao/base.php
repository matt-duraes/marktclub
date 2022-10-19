<?php

return (new \DataBase\DataBase())
    ->id()
    ->char('cod')->tamanho(36)->unico()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_usuario_cliente')->tamanho(9)
    ->nome('nome')
    ->email('email')
    ->telefone('telefone')->null()
    ->char('hash')->tamanho(36)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
