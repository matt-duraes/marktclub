<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->char('hash')->tamanho(32)
    ->text('mensagem')->null()
    ->varchar('codigo')->null()
    ->text('status_http')->null()
    ->varchar('codigo')->tamanho(5)->null()
    ->text('arquivo')->null()
    ->varchar('linha')->tamanho(10)->null()
    ->text('trace')->null()
    ->int('quantidade')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status');
