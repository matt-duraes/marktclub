<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->nome('nome')
    ->varchar('cargo')->tamanho(100)
    ->text('texto')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->int('ordem')->tamanho(9)->null()->padrao(999)
    ->status();
