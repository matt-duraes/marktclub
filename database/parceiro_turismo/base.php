<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->varchar('titulo')->tamanho(30)
    ->varchar('texto')->tamanho(191)
    ->imagem('imagem')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->date('data_inicio')
    ->date('data_final')->null()
    ->status();
