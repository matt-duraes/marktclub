<?php

return (new \DataBase\DataBase())
    ->id()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_parceiro_loja')->tamanho(9)
    ->int('numero_transacao')->tamanho(9)
    ->dinheiro('valor_venda')
    ->date('data_relatorio')
    ->dataCriacao()
    ->dataAtualizacao();
