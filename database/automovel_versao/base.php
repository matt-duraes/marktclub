<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->varchar('titulo')->tamanho(250)
    ->dinheiro('valor_de')
    ->dinheiro('valor_por')
    ->varchar('modelo')->tamanho(100)
    ->imagem('imagem')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
