<?php

return (new \DataBase\DataBase())
    ->id()
    ->cod()
    ->int('tipo')->tamanho(9)
    ->text('valor')
    ->datetime('data_vencimento')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
