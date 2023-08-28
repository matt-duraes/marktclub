<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->varchar('titulo')->tamanho(250)
    ->text('texto')
    ->dataCriacao()
    ->dataAtualizacao()
    ->slug('url', 'titulo')
    ->status();
