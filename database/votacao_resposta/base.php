<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_votacao_pergunta')->tamanho(9)->relacionado(TABELA_VOTACAO_PERGUNTA, 'id')
    ->varchar('titulo')->tamanho(250)
    ->text('texto')->null()
    ->int('pode_nulo')->tamanho(1)
    ->int('escrever_voto')->tamanho(1)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
