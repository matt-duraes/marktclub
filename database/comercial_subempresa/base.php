<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->varchar('nome')->tamanho(100)
    ->cnpj()
    ->status()
    ->dataCriacao()
    ->dataAtualizacao();
