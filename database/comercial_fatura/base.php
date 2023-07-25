<?php

return (new \DataBase\DataBase())
    ->id()
    ->int('id_admin_empresa')->tamanho(9)
    ->dinheiro('valor_real')
    ->dinheiro('valor_pago')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->date('data_vencimento')
    ->date('data_pagamento')->null()
    ->date('data_baixa')->null()
    ->int('paga_com_atraso')->tamanho(1)->null()
    ->status();
