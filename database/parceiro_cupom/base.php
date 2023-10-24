<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->varchar('titulo')
    ->text('texto')
    ->date('data_validade')
    ->int('tipo')->tamanho(1)
    ->varchar('cupom')->null()
    ->varchar('link')->null()
    ->varchar('imagem')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
