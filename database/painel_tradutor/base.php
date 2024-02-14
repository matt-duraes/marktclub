<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->text('termo')
    ->json('traducao')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
