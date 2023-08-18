<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_parceiro_loja')->relacionado(TABELA_PARCEIRO_LOJA, 'id')
    ->json('id_admin_empresa')
    ->varchar('titulo')->tamanho(100)
    ->imagem('imagem')
    ->dataCriacao()
    ->dataAtualizacao()
    ->date('data_inicio')
    ->date('data_final')
    ->status();
