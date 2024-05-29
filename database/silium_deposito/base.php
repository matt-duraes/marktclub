<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->int('id_usuario_cliente')->tamanho(9)->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->nome('nome_titular')
    ->cpf()
    ->email('email')
    ->tinyint('tipo_conta')->tamanho(1)
    ->varchar('banco')->tamanho(32)
    ->varchar('agencia')->tamanho(12)
    ->varchar('conta')->tamanho(12)
    ->float('valor')->null()
    ->int('pontuacao')->tamanho(9)
    ->date('data_deposito')
    ->varchar('documento_anexo')->null()
    ->tinyint('tipo')->tamanho(1)
    ->status()
    ->dataAtualizacao()
    ->dataCriacao();
