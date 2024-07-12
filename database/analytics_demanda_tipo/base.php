<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_demanda_sprint')->relacionado(TABELA_DEMANDA_SPRINT, 'id')
    ->int('tipo')
    ->int('quantidade_tarefa')
    ->int('quantidade_ponto');
