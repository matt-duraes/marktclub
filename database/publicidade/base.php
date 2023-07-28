<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('empresa')->tamanho(9)->replace('id_admin_empresa')
    ->varchar('titulo')->tamanho(256)
    ->varchar('imagem')->tamanho(256)
    ->varchar('link')->tamanho(256)
    ->varchar('target')->tamanho(10)
    ->tinyint('tipo')->tamanho(1)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
