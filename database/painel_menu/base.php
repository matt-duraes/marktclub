<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_painel_menu')->relacionado('painel_menu', 'id')->null()
    ->int('tipo')
    ->varchar('titulo')->tamanho(100)
    ->text('url')->null()
    ->text('icone')->null()
    ->text('menu')->null()
    ->text('permissao')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->int('ordem')->tamanho(3)
    ->status();
