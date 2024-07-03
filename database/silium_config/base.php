<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->tinyint('desconto')->padrao(0)->null()
    ->json('regra_conversao')->null()
    ->json('pontuacao_minima_resgate')->null()
    ->int('validade_pontuacao')->tamanho(3)->null()
    ->dataAtualizacao()
    ->dataCriacao();
