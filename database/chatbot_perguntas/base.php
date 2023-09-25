<?php

return (new \Database\DataBase())
    ->id()
    ->uuid()
    ->text('categoria')
    ->json('pergunta')
    ->text('resposta')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
