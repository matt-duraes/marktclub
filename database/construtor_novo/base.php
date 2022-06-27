<?php

return (new \DataBase\DataBase())
    ->id()
    ->char('cod')->tamanho(36)->unico()
    ->int('empresa')->tamanho(9)
    ->varchar('titulo')->tamanho(100)
    ->imagem('logo')
    ->char('cor')
    ->text('link_site')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
