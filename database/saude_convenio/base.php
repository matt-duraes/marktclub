<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->json('id_admin_empresa')
    ->json('endereco_estado')->null()
    ->json('endereco_cidade')->null()
    ->varchar('titulo')->tamanho(180)
    ->imagem('arquivo_imagem')
    ->slug('url', 'titulo')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
