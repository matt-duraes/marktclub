<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_usuario_cliente')->tamanho(9)->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->nome('nome_titular')
    ->cpf()
    ->email('email')
    ->tinyint('tipo_conta')->tamanho(1)
    ->varchar('banco')->tamanho(32)
    ->varchar('agencia')->tamanho(12)
    ->varchar('conta')->tamanho(12)
    ->int('pontuacao')->tamanho(9)
    ->status()
    ->dataAtualizacao()
    ->dataCriacao();
