<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_usuario_cliente')->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->text('navegar')
    ->text('procura')
    ->text('suporte')
    ->text('atendimento')
    ->json('sistemas_clube')->null()
    ->text('comentario')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
