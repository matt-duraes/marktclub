<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->varchar('titulo')->tamanho(250)
    ->text('texto')
    ->varchar('header_titulo')->tamanho(65)->null()
    ->varchar('header_descricao')->tamanho(155)->null()
    ->json('header_tag')->null()
    ->slug('url', 'titulo')
    ->datetime('data_inicio')
    ->datetime('data_final')->null()
    ->int('permissao_restrita')->tamanho(1)->null()
    ->int('permissao_site')->tamanho(1)->null()
    ->int('local')->tamanho(1)->null()
    ->status();
