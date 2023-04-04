<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->varchar('cod')->tamanho('36')
    ->varchar('tabela')->tamanho(100)
    ->nome('contato')->null()
    ->int('local')->tamanho(1)->null()
    ->int('tipo')->tamanho(1)->null()
    ->varchar('outro')->tamanho(50)->null()
    ->varchar('nome')->tamanho(50)->null()
    ->cpf('documento')->null()
    ->varchar('valor')->tamanho(100)
    ->int('operadora')->tamanho(1)->null()
    ->int('destaque')->tamanho(1)->null();
