<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_usuario_dono')->tamanho(9)->relacionado('usuario_equipe', 'id')
    ->int('id_usuario_equipe')->tamanho(9)->relacionado('usuario_equipe', 'id')
    ->varchar('titulo')->tamanho(100)
    ->text('mensagem')
    ->text('link')->null()
    ->varchar('botao')->null()
    ->varchar('target')->tamanho(50)->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->datetime('data_vializacao')->null()
    ->status();
