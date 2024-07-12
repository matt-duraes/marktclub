<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_demanda_sprint')->relacionado(TABELA_DEMANDA_SPRINT, 'id')
    ->int('id_usuario_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->int('quantidade_demanda')
    ->int('quantidade_tarefa')
    ->int('quantidade_ponto');
