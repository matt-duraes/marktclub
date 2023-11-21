<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')
    ->text('url_1')
    ->text('url_2')
    ->text('url_3')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
