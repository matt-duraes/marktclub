<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->varchar('titulo')->tamanho(100)
    ->json('id_admin_empresa')
    ->int('marktclub')->null()
    ->text('url_1')
    ->text('url_2')->null()
    ->text('url_3')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
