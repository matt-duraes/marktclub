<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->char('id_vinculo')->tamanho(36)
    ->varchar('local_principal')->tamanho(100)
    ->varchar('titulo')->tamanho(150)->null()
    ->dataCriacao();
