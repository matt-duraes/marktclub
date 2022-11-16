<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)
    ->int('id_usuario_equipe')->tamanho(9)->null()
    ->int('id_usuario_cliente')->tamanho(9)
    ->decimal('valor_debito')
    ->date('data_pagamento')->null()
    ->date('data_cobranca')
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
