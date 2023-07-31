<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_usuario')->tamanho(9)
    ->float('comissao_usuario')
    ->varchar('moeda')->tamanho(10)
    ->date('data_compra')
    ->status()
    ->dataAtualizacao()
    ->dataCriacao();
