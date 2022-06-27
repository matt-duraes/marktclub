<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->text('id_api_app')
    ->nome('nome_usuario')
    ->varchar('login_usuario')->tamanho(100)->unico()
    ->varchar('salt')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
