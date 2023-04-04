<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->json('id_parceiro')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status()->null();
