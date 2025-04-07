<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_admin_subempresa')->tamanho(9)->null()
    ->varchar('codigo')->tamanho(30)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
