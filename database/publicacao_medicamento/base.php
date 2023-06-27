<?php

return (new \DataBase\DataBase())
    ->id()
    ->char('cod')->tamanho(36)
    ->char('titulo')->tamanho(36)
    ->text('desconto_texto')->null()
    ->imagem('imagem')->tamanho(36)
    ->json('empresa')
    ->text('link_site')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
