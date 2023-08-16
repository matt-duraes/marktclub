<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_usuario_cliente')->tamanho(9)
    ->date('titular')
    ->int('quantidade_dependente')->tamanho(3)->null()
    ->int('operadora')->tamanho(2)
    ->int('acomodacao')->tamanho(2)
    ->int('plano')->tamanho(2)->null()
    ->int('regiao')->tamanho(2)->null()
    ->varchar('valor_titular')->tamanho(256)
    ->json('lista_dependente')->null()
    ->varchar('valor_total')->tamanho(256)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
