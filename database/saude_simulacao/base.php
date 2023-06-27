<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_usuario')->tamanho(9)
    ->date('data_nascimento')
    ->int('quantidade_dependentes')->tamanho(3)->null()
    ->tinyint('operadora')->tamanho(2)
    ->varchar('acomodacao')->tamanho(256)
    ->tinyint('plano')->null()
    ->tinyint('regiao')->tamanho(2)->null()
    ->varchar('valor_titular')->tamanho(256)
    ->json('valor_dependentes')
    ->varchar('valor_total')->tamanho(256)
    ->status()
    ->dataCriacao()
    ->dataAtualizacao();
