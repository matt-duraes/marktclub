<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->text('texto')->null()
    ->imagem('bg_frente')->null()
    ->imagem('bg_fundo')->null()
    ->text('texto_perdido')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
