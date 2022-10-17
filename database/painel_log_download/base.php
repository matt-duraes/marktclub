<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_usuario_equipe')->tamanho(9)->relacionado('usuario_equipe', 'id',)
    ->varchar('app')->tamanho(50)
    ->text('request')->null()
    ->int('quantidade')->tamanho(11)
    ->dataCriacao();
