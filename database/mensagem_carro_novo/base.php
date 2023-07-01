<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_usuario_cliente')->null()
    ->int('id_admin_empresa')->null()
    ->varchar('produto')->tamanho(100)
    ->varchar('modelo')->tamanho(100)
    ->varchar('versao')->tamanho(100)
    ->varchar('cor')->tamanho(100)
    ->varchar('cidade')->tamanho(100)
    ->text('mensagem')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
