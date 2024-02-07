<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_usuario_equipe')->tamanho(11)
    ->int('id_admin_empresa')->tamanho(11)
    ->json('obrigatorio')->null()
    ->varchar('arquivo')->tamanho(36)
    ->int('erro')->null()
    ->int('novo')->null()
    ->int('atualizado')->null()
    ->int('tipo')->null()
    ->status()
    ->dataCriacao()
    ->dataAtualizacao();
