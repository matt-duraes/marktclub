<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_usuario_cliente')->tamanho(9)->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->int('id_silium_saque')->tamanho(9)->relacionado(TABELA_SILIUM_SAQUE, 'id')
    ->float('valor')->null()
    ->date('data_deposito')
    ->varchar('documento_anexo')->null()
    ->status()
    ->dataAtualizacao()
    ->dataCriacao();
