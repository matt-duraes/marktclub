<?php

return (new \DataBase\DataBase())
    ->id()
    ->char('cod')->tamanho(36)
    ->nome('titulo')->null()
    ->nome('razao_social')
    ->nome('nome_fantasia')
    ->cnpj('cnpj')->unico()
    ->nome('responsavel_nome')
    ->cpf('responsavel_cpf')
    ->email('responsavel_email')->null()
    ->telefone('responsavel_telefone')->null()
    ->text('site')->null()
    ->imagem('imagem_arquivo')->null()
    ->slug('slug', 'titulo')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
