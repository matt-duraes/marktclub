<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('grupo')->tamanho(2)->null()
    ->nome('nome')
    ->varchar('cargo')->tamanho(100)
    ->text('texto')->null()
    ->imagem('imagem')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->int('ordem')->tamanho(9)->null()->padrao(999)
    ->status();
