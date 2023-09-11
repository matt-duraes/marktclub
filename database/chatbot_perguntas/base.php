<?php

return (new \Database\DataBase())
    ->id()
    ->uuid()
    ->int('categoria')->relacionado(TABELA_CHATBOT_CATEGORIA, 'id')
    ->varchar('pergunta')
    ->text('resposta')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
