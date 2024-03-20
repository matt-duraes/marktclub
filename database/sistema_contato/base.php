<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->char('id_vinculo')->tamanho('36')
    ->varchar('local_principal')->tamanho(100)
    ->varchar('local_secundario')->tamanho(100)
    ->nome('titulo')->tamanho(50)->null()
    ->nome('nome')->tamanho(50)->null()
    ->cpf('cpf')->null()
    ->int('tipo')->tamanho(1)
    ->varchar('valor')->tamanho(100)
    ->int('whatsapp')->tamanho(1)->null()
    ->int('principal')->tamanho(1)->null();
;
