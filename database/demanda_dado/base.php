<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->relacionado(TABELA_EMPRESA_NOVO, 'id')
    ->int('id_usuario_equipe')->relacionado(TABELA_USUARIO_EQUIPE, 'id')
    ->varchar('titulo')->tamanho(100)
    ->int('tipo')
    ->dataCriacao()
    ->dataAtualizacao()
    ->json('arquivo')->null()
    ->json('seguindo')->null()
    ->int('com_prazo')->null()
    ->date('data_entrega')->null()
    ->int('ordem')->null()
    ->status();
