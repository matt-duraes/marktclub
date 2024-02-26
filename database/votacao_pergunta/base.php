<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_votacao_dado')->tamanho(9)->relacionado(TABELA_VOTACAO_DADO, 'id')
    ->int('tipo')->tamanho(1)
    ->varchar('titulo')->tamanho(250)
    ->dataCriacao()
    ->dataAtualizacao()
    ->int('ordem')->tamanho(4)->padrao(9999)
    ->status();
