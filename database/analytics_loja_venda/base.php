<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_admin_subempresa')->tamanho(9)->null()
    ->int('id_parceiro_loja')->tamanho(9)
    ->int('numero_transacao')->tamanho(9)
    ->dinheiro('valor_venda')
    ->date('data_relatorio')
    ->dataCriacao()
    ->dataAtualizacao();
