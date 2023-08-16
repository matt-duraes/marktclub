<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_usuario')->tamanho(9)->replace('id_usuario_cliente')
    ->date('titular')
    ->int('quantidade_dependentes')->tamanho(3)->null()
    ->tinyint('operadora')->tamanho(2)
    ->varchar('acomodacao')->tamanho(256)
    ->varchar('plano')->tamanho(64)->null()
    ->varchar('regiao')->tamanho(64)->null()
    ->varchar('valor_titular')->tamanho(256)
    ->json('dependentes')
    ->varchar('valor_total')->tamanho(256)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
