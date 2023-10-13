<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->text('texto')->null()
    ->text('texto_perdido')->null()
    ->imagem('bg_frente')->null()
    ->imagem('bg_fundo')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
