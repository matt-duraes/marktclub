<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->varchar('titulo')->tamanho(250)
    ->text('texto')
    ->varchar('header_titulo')->tamanho(65)->null()
    ->varchar('header_descricao')->tamanho(155)->null()
    ->json('header_tag')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->slug('url', 'titulo')
    ->status();
