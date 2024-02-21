<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->int('noticia_1')
    ->int('noticia_2')
    ->int('noticia_3')
    ->dataCriacao()
    ->dataAtualizacao();
