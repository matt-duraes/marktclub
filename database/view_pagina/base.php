<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->varchar('titulo')->tamanho(250)
    ->varchar('url')->tamanho(200)
    ->json('html')->null()
    ->status();
