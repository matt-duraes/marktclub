<?php

use Database\DataBase;

return (new DataBase())
    ->id()
    ->char('cod')->tamanho(36)
    ->int('empresa')
    ->int('usuario')
    ->char('vinculo')->tamanho(36)
    ->int('tipo')->tamanho(1)
    ->status()
    ->dataCriacao()
    ->dataAtualizacao();
