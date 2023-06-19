<?php

return (new \DataBase\DataBase())
    ->id()
    ->int('id_usuario_equipe')->relacionado(TABELA_USUARIO_EQUIPE, 'id')
    ->int('id_admin_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->decimal('valor')
    ->dataCriacao();
