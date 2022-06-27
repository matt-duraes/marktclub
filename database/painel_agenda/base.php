<?php

return (new \DataBase\DataBase())
    ->id()
    ->int('id_usuario_equipe')->tamanho(11)->relacionado('usuario_equipe', 'id')
    ->date('data')
    ->time('inicio')
    ->time('final')
    ->dataCriacao()
    ->dataAtualizacao();
