<?php

return (new \Database\DataBase())
    ->id()
    ->uuid()
    ->varchar('categoria')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
