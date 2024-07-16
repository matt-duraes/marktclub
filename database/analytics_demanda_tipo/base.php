<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_demanda_sprint')->relacionado(TABELA_DEMANDA_SPRINT, 'id')
    ->int('tipo_valor')
    ->varchar('tipo_nome')->tamanho(100)
    ->int('quantidade_tarefa')
    ->int('quantidade_ponto')
    ->float('dificuldade')
    ->dataCriacao();
