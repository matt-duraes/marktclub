<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_usuario')->tamanho(9)
    ->nome('nome')
    ->cpf()
    ->varchar('banco')->tamanho(12)
    ->varchar('agencia')->tamanho(6)
    ->varchar('conta')->tamanho(8)
    ->tinyint('tipo_conta')->tamanho(1)
    ->varchar('comissao')->tamanho(12)
    ->varchar('valor')->tamanho(12)
    ->date('data_deposito')->null()
    ->status()
    ->dataAtualizacao()
    ->dataCriacao();
