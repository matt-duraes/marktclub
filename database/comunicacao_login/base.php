<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->varchar('titulo')->tamanho(100)
    ->json('id_admin_empresa')
    ->int('padrao')->null()
    ->imagem('arquivo_1')
    ->imagem('arquivo_2')->null()
    ->imagem('arquivo_3')->null()
    ->dataCriacao()
    ->dataAtualizacao();
