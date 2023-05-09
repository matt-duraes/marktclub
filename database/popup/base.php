<?php

use Database\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->slug('slug', 'titulo')->tamanho(256)
    ->varchar('titulo')->tamanho(256)
    ->varchar('subtitulo')->tamanho(256)->null()
    ->longtext('texto')
    ->json('formulario')->null()
    ->varchar('imagem')->tamanho(256)->null()
    ->datetime('data_vencimento')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
