<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->json('id_admin_empresa')
    ->int('id_parceiro_loja')->relacionado(TABELA_PARCEIRO_LOJA, 'id')
    ->varchar('titulo')->tamanho(250)
    ->text('texto')->null()
    ->text('url')->null()
    ->imagem('imagem')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
