<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_votacao_dado')->tamanho(9)->relacionado(TABELA_VOTACAO_DADO, 'id', DataBase::CASCADE, DataBase::CASCADE)
    ->int('tipo')->tamanho(1)
    ->varchar('titulo')->tamanho(250)
    ->text('texto')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->int('ordem')->tamanho(4)->padrao(9999);
