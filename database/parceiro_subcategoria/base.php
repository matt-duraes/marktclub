<?php

return (new \DataBase\DataBase())
    ->id()
    ->cod()
    ->json('empresa')
    ->int('categoria')->tamanho(2)
    ->varchar('titulo')
    ->json('tag')
    ->imagem('imagem')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->int('preferencia')->tamanho(1)->null()
    ->int('menu')->tamanho(1)->null()
    ->slug('url', 'titulo');
