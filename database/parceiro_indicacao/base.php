<?php

return (new \Database\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->null()
    ->int('id_usuario_cliente')->null()
    ->varchar('nome')
    ->telefone('telefone')
    ->email('email')
    ->text('mensagem')
    ->status()
    ->dataCriacao()
    ->dataAtualizacao();
