<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->char('vinculo')->null()
    ->varchar('titulo')->tamanho(250)
    ->varchar('detalhe')->tamanho(250)
    ->varchar('cor')->tamanho(100)
    ->text('valor')->null()
    ->decimal('valor_off')->null()
    ->int('tipo')->tamanho(1)->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
