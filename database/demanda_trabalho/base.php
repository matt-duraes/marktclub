<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_demanda_tarefa')->relacionado(TABELA_DEMANDA_TAREFA, 'id')
    ->int('id_usuario_equipe')->relacionado(TABELA_USUARIO_EQUIPE, 'id')
    ->dataCriacao()
    ->datetime('data_trabalho')
    ->int('minuto_trabalhado')->null()
    ->status();
