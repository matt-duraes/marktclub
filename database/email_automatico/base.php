<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_usuario_cliente')->tamanho(9)
    ->int('tipo')->tamanho(2)
    ->nome('nome')
    ->email('email')
    ->text('dado')->null()
    ->text('id_relacionado')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->datetime('data_para_enviar')->null()
    ->datetime('data_envio')->null()
    ->status();
