<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_usuario_equipe')->tamanho(9)->relacionado('usuario_equipe', 'id')
    ->varchar('titulo')->tamanho(100)
    ->varchar('texto')->tamanho(250)
    ->dataCriacao()
    ->datetime('data_vializacao')->null()
    ->status();
