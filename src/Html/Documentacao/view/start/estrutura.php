<?php

$Doc = new DocumentacaoConfig\Fw('ESTRUTURA DO FRAMEWORK', 'O Framework foi projetado para ter uma estrutura mais simples e organizada possível, tanto para desenvolver como para atualizar o kernel dos FW, para isso, siga a seguinte estrutura.');

$Doc
    ->tabela(function () use ($Doc) {
        $Doc
            ->trTitulo(['Diretório', 'Descrição'])
            ->tr(['app', 'Classes gerais do sistema'])
            ->tr(['app/Classes', 'Classes gerais que podem ser usados em qualquer rota'])
            ->tr(['app/Controllers', 'Classes com os Models do sistema'])
            ->tr(['app/Helpers', 'Classes com os Helpers do sistema'])
            ->tr(['app/Middlewares', 'Classes com os Middlewares do sistema'])
            ->tr(['app/Models', 'Classes com os Models do sistema'])
            ->tr(['database', 'Bancos de dados do sistema'])
            ->tr(['files', 'Arquivos privados do sistema'])
            ->tr(['public', 'Arquivos públicos'])
            ->tr(['resources', 'Arquivos padrões do sistema'])
            ->tr(['resources/css', 'Arquivos CSS padrões do sistema'])
            ->tr(['resources/js', 'Arquivos JS padrões do sistema'])
            ->tr(['resources/php', 'Arquivos PHP padrões do sistema'])
            ->tr(['routes', 'Rotas do sistema'])
            ->tr(['src', 'Arquivos do sistema, não modificar nada desse diretório'])
            ->tr(['tests', 'Testes do sistema'])
            ->tr(['views', 'Arquivos originais da view'])
            ->tr(['views/images', 'Imagens do sistema'])
            ->tr(['views/pages', 'Páginas do sistema'])
            ->tr(['views/templates', 'Templates do sistema']);
    });
echo $Doc;
