<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->int('id_usuario_cliente')->tamanho(9)->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->text('navegar')
    ->text('procura')
    ->text('suporte')
    ->text('atendimento')
    ->json('sistemas_clube')->null()
    ->text('comentario')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
