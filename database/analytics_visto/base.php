<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_usuario_cliente')->relacionado(TABELA_USUARIO_CLIENTE, 'id')
    ->int('id_relacionado')->null()
    ->int('tipo')
    ->varchar('nome')->tamanho(100)
    ->dataCriacao();
