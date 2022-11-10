<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_usuario_cliente')
    ->int('ponto_solicitado')->tamanho(9)
    ->int('pedido_codigo')->tamanho(9)->unico()
    ->varchar('voucher')->tamanho(50)->null()
    ->text('mensagem')->null()
    ->dataAtualizacao()
    ->datetime('data_solicitacao')
    ->datetime('data_voucher')->null()
    ->status();
