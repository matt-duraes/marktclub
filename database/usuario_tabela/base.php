<?php

use DataBase\DataBase;

return (new DataBase())
    ->uuid()
    ->varchar('nome')->tamanho(50)->null()
    ->int('id_usuario_equipe')->tamanho(11)
    ->int('id_admin_empresa')->tamanho(11)
    ->varchar('arquivo')->tamanho(36)
    ->int('erro')->null()
    ->int('novo')->null()
    ->int('atualizado')->null()
    ->int('tipo')->null()
    ->status()
    ->dataCriacao()
    ->dataAtualizacao();
