<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->slug('slug', 'titulo')->tamanho(256)
    ->varchar('titulo')->tamanho(256)
    ->varchar('subtitulo')->tamanho(256)->null()
    ->longtext('texto')
    ->json('formulario')->null()
    ->varchar('imagem')->tamanho(256)->null()
    ->datetime('data_expiracao')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
