<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->nome('nome')
    ->email('email')
    ->text('arquivo')
    ->dataCriacao()
    ->dataAtualizacao()
    ->datetime('data_vencimento')
    ->char('codigo_email')->tamanho(8)->null()
    ->char('codigo_autorizacao')->tamanho(36)->null()
    ->status();
