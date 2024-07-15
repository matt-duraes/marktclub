<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_demanda_dado')->relacionado(TABELA_DEMANDA_DADO, 'id')
    ->int('id_usuario_equipe')->relacionado(TABELA_USUARIO_EQUIPE, 'id')->null()
    ->varchar('titulo')->tamanho(100)
    ->text('texto')
    ->int('tipo')
    ->dataCriacao()
    ->dataAtualizacao()
    ->datetime('data_producao_inicio')->null()
    ->datetime('data_producao_final')->null()
    ->int('minuto_producao_estimada')->null()
    ->int('minuto_producao_real')->null()
    ->int('dificuldade')->null()
    ->text('like')->null()
    ->status();
