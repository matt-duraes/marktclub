<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_usuario_equipe')->tamanho(9)
    ->varchar('titulo')->tamanho(191)
    ->varchar('texto')->tamanho(250)->null()
    ->imagem('imagem')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->slug('url', 'titulo')
    ->datetime('data_inicio')
    ->datetime('data_final')->null()
    ->int('permissao_restrita')->tamanho(1)->null()
    ->int('permissao_site')->tamanho(1)->null()
    ->status();
