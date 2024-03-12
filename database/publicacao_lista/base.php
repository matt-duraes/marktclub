<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->varchar('titulo')->tamanho(100)
    ->text('texto')->null()
    ->json('lista')->null()
    ->imagem('imagem')->null()
    ->int('grupo')->tamanho(2)
    ->dataCriacao()
    ->dataAtualizacao()
    ->int('ordem')->tamanho(4)->padrao(9999)
    ->status();
