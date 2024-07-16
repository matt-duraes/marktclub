<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_demanda_sprint')->relacionado(TABELA_DEMANDA_SPRINT, 'id')
    ->int('id_admin_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->varchar('empresa_nome')->tamanho(100)
    ->int('quantidade_demanda')
    ->int('quantidade_tarefa')
    ->int('quantidade_ponto')
    ->float('dificuldade')
    ->dataCriacao();
