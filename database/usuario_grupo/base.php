<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->varchar('indice')->tamanho(100)
    ->varchar('titulo')->tamanho(100)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
