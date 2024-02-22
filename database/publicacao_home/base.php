<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->char('noticia_1')->tamanho(36)->null()
    ->char('noticia_2')->tamanho(36)->null()
    ->char('noticia_3')->tamanho(36)->null()
    ->dataCriacao()
    ->dataAtualizacao();
