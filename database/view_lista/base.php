<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_view_pagina')->relacionado(TABELA_VIEW_PAGINA, 'id')
    ->int('tipo')->tamanho(2)
    ->int('local')->tamanho(2)
    ->varchar('titulo')->tamanho(250)->null()
    ->text('texto')->null()
    ->text('link')->null()
    ->int('target')->tamanho(1)->null()
    ->char('arquivo')->tamanho(36)->null()
    ->botao('api_status')->null()
    ->varchar('api_scope')->tamanho(100)->null()
    ->varchar('api_uri')->tamanho(100)->null()
    ->varchar('api_metodo')->tamanho(4)->null()
    ->varchar('api_body')
    ->status();
