<?php

return (new \DataBase\DataBase())
    ->id()
    ->char('cod')->tamanho(36)
    ->int('empresa')->tamanho(9)
    ->int('usuario')->tamanho(9)
    ->varchar('tipo')->tamanho(1)
    ->varchar('valor')->tamanho(50)->null()
    ->char('vinculo')->tamanho(36)
    ->varchar('codigo')->tamanho(20)->null()
    ->varchar('quantidade_voucher')->tamanho(1)->null()
    ->cpf('documento_dependente')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->date('data_vencimento')->null()
    ->datetime('data_validacao')->null()
    ->status();
