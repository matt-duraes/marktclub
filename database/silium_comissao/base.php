<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_empresa')->tamanho(9)
    ->int('id_usuario')->tamanho(9)
    ->float('comissao_usuario')
    ->date('data_compra')
    ->varchar('moeda')->tamanho(10)
    ->status()
    ->dataAtualizacao()
    ->dataCriacao();
