<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')
    ->int('id_usuario')
    ->char('vinculo')->tamanho(36)
    ->tinyint('tipo')->tamanho(1)
    ->status()
    ->dataCriacao()
    ->dataAtualizacao();
