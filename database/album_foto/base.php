<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_album_dado')->relacionado(TABELA_ALBUM_DADO, 'id')
    ->varchar('titulo')->null()
    ->imagem('imagem')
    ->dataCriacao()
    ->dataAtualizacao()
    ->int('ordem')->tamanho(4)->padrao(9999)
    ->status();
