<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->int('id_usuario_cliente')->tamanho(9)->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->tinyint('fidelidade')
    ->tinyint('produtos')
    ->tinyint('gasto')
    ->tinyint('importancia')
    ->tinyint('cashback')
    ->tinyint('frequencia')
    ->tinyint('resgate')
    ->tinyint('desconto')
    ->tinyint('experiencia')
    ->tinyint('indicaria')
    ->dataCriacao()
    ->dataAtualizacao();
