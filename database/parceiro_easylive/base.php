<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->json('id_admin_empresa')
    ->int('tipo')->tamanho(1)
    ->varchar('titulo')->tamanho(150)
    ->imagem('imagem')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->date('data_validade')->null()
    ->status();
