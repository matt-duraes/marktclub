<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->null()->tamanho(9)->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->int('id_usuario_cliente')->null()->tamanho(9)->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->int('id_parceiro_loja')->tamanho(9)->relacionado(TABELA_PARCEIRO_LOJA, 'id')->null()
    ->varchar('nome')->tamanho(256)->null()
    ->telefone('telefone')->null()
    ->email('email')->null()
    ->text('mensagem')->null()
    ->status()
    ->dataCriacao()
    ->dataAtualizacao();
