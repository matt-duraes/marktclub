<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->int('id_usuario_equipe')->relacionado(TABELA_USUARIO_EQUIPE, 'id')
    ->text('navegar')
    ->text('procura')
    ->text('suporte')
    ->text('comentario')->null()
    ->text('atendimento')
    ->json('sistemas')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
