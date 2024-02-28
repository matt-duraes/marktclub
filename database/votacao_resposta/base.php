<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_votacao_pergunta')->tamanho(9)->relacionado(TABELA_VOTACAO_PERGUNTA, 'id', DataBase::CASCADE, DataBase::CASCADE)
    ->varchar('titulo')->tamanho(250)
    ->text('texto')->null()
    ->int('pode_nulo')->tamanho(1)->null()
    ->int('escrever_voto')->tamanho(1)->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->int('ordem')->tamanho(4)->padrao(9999);
