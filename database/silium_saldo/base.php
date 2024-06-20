<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_usuario_cliente')->tamanho(9)->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->int('saldo_silium')->tamanho(9)
    ->date('data_validade')->null()
    ->dataAtualizacao()
    ->dataCriacao();
