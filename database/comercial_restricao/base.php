<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->varchar('titulo')
    ->dataCriacao()
    ->dataAtualizacao();
