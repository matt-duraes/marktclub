<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->int('id_usuario_cliente')->tamanho(9)->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->varchar('endereco_estado')->tamanho(100)
    ->varchar('endereco_cidade')->tamanho(100)
    ->varchar('montadora')->tamanho(100)
    ->varchar('modelo')->tamanho(100)
    ->varchar('versao')->tamanho(100)->null()
    ->varchar('cor')->tamanho(100)->null()
    ->text('mensagem')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
