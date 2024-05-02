<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->int('id_usuario_equipe')->tamanho(9)->null()->relacionado(TABELA_USUARIO_EQUIPE, 'id', DataBase::SET_NULL, DataBase::SET_NULL)
    ->int('prospeccao_dia')->tamanho(4)
    ->int('prospeccao_mes')->tamanho(4)
    ->int('prospeccao_total')->tamanho(4)
    ->int('problema_dia')->tamanho(4)
    ->int('problema_mes')->tamanho(4)
    ->int('problema_total')->tamanho(4)
    ->int('cancelado_dia')->tamanho(4)
    ->int('cancelado_mes')->tamanho(4)
    ->int('cancelado_total')->tamanho(4)
    ->int('sem_interesse_dia')->tamanho(4)
    ->int('sem_interesse_mes')->tamanho(4)
    ->int('sem_interesse_total')->tamanho(4)
    ->int('concluido_dia')->tamanho(4)
    ->int('concluido_mes')->tamanho(4)
    ->int('concluido_total')->tamanho(4)
    ->date('data_acesso');
