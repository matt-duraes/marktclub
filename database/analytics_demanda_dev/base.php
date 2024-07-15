<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_demanda_sprint')->relacionado(TABELA_DEMANDA_SPRINT, 'id')
    ->int('id_usuario_dev')->relacionado(TABELA_USUARIO_EQUIPE, 'id')
    ->varchar('usuario_nome')->tamanho(100)
    ->int('quantidade_tarefa')
    ->int('quantidade_ponto')
    ->float('dificuldade');
