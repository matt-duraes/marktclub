<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('empresa')
    ->int('usuario')
    ->char('vinculo')->tamanho(36)
    ->int('tipo')->tamanho(1)
    ->status()
    ->dataCriacao()
    ->dataAtualizacao();
