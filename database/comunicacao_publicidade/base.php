<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_parceiro_loja')->relacionado(TABELA_PARCEIRO_LOJA, 'id')->null()
    ->varchar('titulo')->tamanho(100)
    ->imagem('imagem_desktop')->null()
    ->imagem('imagem_mobile')->null()
    ->int('tipo')->tamanho(1)
    ->text('link')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->date('data_inicio')
    ->date('data_final')
    ->int('ordem')->tamanho(4)->padrao(9999)
    ->status();
