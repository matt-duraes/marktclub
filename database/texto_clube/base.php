<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->json('id_admin_empresa')
    ->varchar('titulo')->tamanho(100)
    ->text('texto')
    ->varchar('header_titulo')->tamanho(65)->null()
    ->varchar('header_descricao')->tamanho(155)->null()
    ->json('header_tag')->null()
    ->int('tipo')->tamanho(2)
    ->dataCriacao()
    ->dataAtualizacao()
    ->int('ordem')->tamanho(4)->null()->padrao(9999)
    ->status();
