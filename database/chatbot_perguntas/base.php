<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->text('categoria')
    ->json('pergunta')
    ->text('resposta')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
