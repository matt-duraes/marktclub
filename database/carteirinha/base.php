<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')->unico()
    ->imagem('bg_frente')
    ->imagem('bg_fundo')->null()
    ->int('nome')->tamanho(1)->null()
    ->int('cpf')->tamanho(1)->null()
    ->int('matricula')->tamanho(1)->null()
    ->int('data_nascimento')->tamanho(1)->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
