<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_parceiro_loja')->relacionado(TABELA_PARCEIRO_LOJA, 'id')
    ->varchar('titulo')->tamanho(250)
    ->text('texto')->null()
    ->slug('url', 'titulo')
    ->imagem('imagem')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->date('data_inicio')
    ->date('data_final')
    ->status();
