<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_parceiro_loja')->tamanho(9)->relacionado(TABELA_PARCEIRO_LOJA, 'id')
    ->slug('url', 'titulo')
    ->varchar('titulo')->tamanho(250)
    ->text('texto')->null()
    ->imagem('imagem')->null()
    ->date('data_inicio')
    ->date('data_final')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
