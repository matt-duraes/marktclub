<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->json('id_admin_empresa')
    ->varchar('titulo')->tamanho(250)
    ->varchar('url')->tamanho(200)
    ->status();
