<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->varchar('titulo')->tamanho(100)->null()
    ->text('permissao')
    ->text('configuracao')->null()
    ->longtext('campo_obrigatorio')->null()
    ->longtext('campo_permitido')->null()
    ->json('upload_grupo')->null()
    ->dataCriacao()
    ->dataAtualizacao();
