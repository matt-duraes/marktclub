<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->json('regra_conversao')->null()
    ->json('pontuacao_minima_resgate')->null()
    ->int('validade_pontuacao')->tamanho(3)->null()
    ->dataAtualizacao()
    ->dataCriacao();
