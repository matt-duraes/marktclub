<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_empresa')
    ->int('id_usuario')
    ->char('vinculo')->tamanho(36)
    ->int('tipo')->tamanho(1)
    ->status()
    ->dataCriacao()
    ->dataAtualizacao();
