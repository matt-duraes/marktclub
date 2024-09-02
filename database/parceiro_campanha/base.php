<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_parceiro_loja')->relacionado(TABELA_PARCEIRO_LOJA, 'id')
    ->varchar('titulo')->tamanho(30)
    ->varchar('texto')->tamanho(191)
    ->imagem('imagem_desktop')->null()
    ->imagem('imagem_mobile')->null()
    ->text('link')
    ->dataCriacao()
    ->dataAtualizacao()
    ->date('data_inicio')
    ->date('data_final')->null()
    ->status();
