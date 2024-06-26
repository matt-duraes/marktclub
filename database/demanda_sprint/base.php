<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->json('id_demanda')->null()
    ->json('id_demanda_inicio')->null()
    ->json('id_demanda_retirada')->null()
    ->json('id_demanda_adicionada')->null()
    ->varchar('titulo')->tamanho(100)
    ->text('texto_inicio')
    ->text('texto_final')->null()
    ->date('data_inicio')->null()
    ->date('data_final')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
