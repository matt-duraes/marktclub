<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_usuario_cliente')->tamanho(9)->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->nome('nome_titular')->null()
    ->cpf()->null()
    ->email('email')
    ->tinyint('tipo_conta')->tamanho(1)->null()
    ->varchar('banco')->tamanho(32)->null()
    ->varchar('agencia')->tamanho(12)->null()
    ->varchar('conta')->tamanho(12)->null()
    ->int('pontuacao')->tamanho(9)->null()
    ->float('valor')->null()
    ->date('data_deposito')->null()
    ->varchar('documento_anexo')->null()
    ->tinyint('tipo_operacao')->tamanho(1)
    ->tinyint('tipo_resgate')->tamanho(1)
    ->status()
    ->dataAtualizacao()
    ->dataCriacao();
