<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->int('id_usuario_cliente')->tamanho(11)->zero()
    ->int('ponto_solicitado')->tamanho(9)
    ->varchar('voucher')->tamanho(50)->null()
    ->text('mensagem')->null()
    ->dataAtualizacao()
    ->datetime('data_solicitacao')
    ->datetime('data_voucher')->null()
    ->status();
