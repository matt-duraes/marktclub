<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->json('id_admin_empresa')
    ->varchar('titulo')->tamanho(100)
    ->text('texto_descricao')
    ->text('texto_restricao')->null()
    ->text('texto_outro')->null()
    ->text('comissao_minima')
    ->text('comissao_maxima')
    ->slug('url', 'titulo')
    ->text('link_site')
    ->text('imagem')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
