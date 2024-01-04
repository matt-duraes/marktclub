<?php

use DataBase\DataBase;

return (new DataBase())
    ->id()
    ->uuid()
    ->int('id_admin_empresa')->tamanho(9)->relacionado(TABELA_COMERCIAL_EMPRESA, 'id')
    ->varchar('titulo_painel')->tamanho(100)
    ->varchar('titulo')->tamanho(60)
    ->varchar('descricao')->tamanho(160)
    ->imagem('logo_principal')
    ->imagem('favicon')->null()
    ->varchar('template')->tamanho(100)
    ->telefone('contato_telefone')->null()
    ->telefone('contato_celular')->null()
    ->telefone('contato_whatsapp')->null()
    ->email('contato_email')->null()
    ->varchar('contato_endereco')->null()
    ->imagem('mapa_arquivo')->null()
    ->text('mapa_link')->null()
    ->char('cor_principal')->tamanho(7)
    ->text('rede_youtube')->null()
    ->text('rede_facebook')->null()
    ->text('rede_instagram')->null()
    ->text('rede_x')->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->status();
