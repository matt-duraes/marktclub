<?php

return (new \DataBase\DataBase())
    ->id()
    ->cod()
    ->int('tipo')->tamanho(9)
    ->varchar('valor')->tamanho(150)
    ->datetime('data_vencimento')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
