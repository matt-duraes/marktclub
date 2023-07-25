<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->json('empresa')
    ->varchar('titulo')->tamanho(250)
    ->text('texto')->null()
    ->varchar('montadora')->tamanho(250)
    ->text('modelo')->null()
    ->text('url')->null()
    ->int('principal')->tamanho(1)->null()
    ->int('banner')->tamanho(1)->null()
    ->imagem('imagem')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
