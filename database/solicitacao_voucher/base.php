<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->cod()
    ->int('empresa')->tamanho(9)->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->int('usuario')->tamanho(9)->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->char('vinculo')->tamanho(36)
    ->varchar('titulo')->null()
    ->int('tipo')->tamanho(1)
    ->int('tipo_usuario')->tamanho(1)
    ->varchar('valor')->tamanho(50)->null()
    ->varchar('codigo')->tamanho(20)->null()
    ->varchar('quantidade_voucher')->tamanho(1)->null()
    ->cpf('documento_dependente')->null()
    ->date('data_vencimento')->null()
    ->datetime('data_validacao')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
