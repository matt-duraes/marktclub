<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->varchar('titulo')
    ->text('texto')
    ->json('id_comercial_empresa')
    ->dataCriacao()
    ->dataAtualizacao();
