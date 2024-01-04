<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_site_menu')->relacionado(TABELA_SITE_MENU, 'id')->null()
    ->int('tipo')->tamanho(1)
    ->int('id_admin_empresa')->tamanho(9)->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->varchar('titulo')->tamanho(80)
    ->text('link')->null()
    ->int('target')->tamanho(1)->null()
    ->int('ordem')->tamanho(3)
    ->status();
