<?php

return (new \Database\DataBase())
    ->id()
    ->uuid()
    ->nome('nome_indicado')
    ->telefone('telefone_indicado')
    ->email('email_indicado')
    ->text('mensagem')
    ->status()
    ->dataCriacao()
    ->dataAtualizacao();
