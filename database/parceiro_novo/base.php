<?php

return (new \DataBase\DataBase())
    ->id()
    ->char('cod')->tamanho(36)
    ->json('empresa')
    ->char('titulo')->tamanho(36)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status()->null();
