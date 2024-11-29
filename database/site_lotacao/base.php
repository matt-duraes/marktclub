<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->slug('slug', 'titulo')->tamanho(256)
    ->varchar('titulo')->tamanho(256)
    ->int('principal')->tamanho(1)->null()
    ->status()
    ->dataCriacao()
    ->dataAtualizacao();
