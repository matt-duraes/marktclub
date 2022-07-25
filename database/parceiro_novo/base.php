<?php

return (new \DataBase\DataBase())
    ->id()
    ->char('cod')->tamanho(36)
    ->json('empresa')
    ->json('destaque')
    ->int('categoria_principal')->tamanho(2)
    ->json('categoria_todas')
    ->char('titulo')->tamanho(36)
    ->varchar('url')
    ->imagem('imagem')->tamanho(36)
    ->varchar('desconto')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status()->null();
