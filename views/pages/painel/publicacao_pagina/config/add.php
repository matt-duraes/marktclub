<?php

$Painel = new PainelConfig\Add(app: 'publicidade_pagina', acao: $acao);
$diretorioImagem = sessao('PAINEL.upload_grupo')['imagem'] ?? '';
$diretorioArquivo = sessao('PAINEL.upload_grupo')['arquivo'] ?? '';

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Título', function () use ($Painel) {
        $Painel
            ->input(
                name: 'titulo',
                label: 'Título',
                placeholder: 'Digite um título',
                contador: 200,
                obrigatorio: true
            );
    });
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('SEO', function () use ($Painel) {
        $Painel
            ->input(
                name: 'header_titulo',
                label: 'Título',
                placeholder: 'Digite um título',
                contador: 65
            )
            ->input(name: 'header_descricao', label: 'Descrição', placeholder: 'Digite uma descrição', contador: 155)
            ->tag(name: 'header_tag', label: 'Tags', placeholder: 'Digite sua tags');
    });
});

$Painel->coluna(callback: function () use ($Painel, $diretorioImagem, $diretorioArquivo) {
    $Painel->fieldset('Texto principal', function () use ($Painel, $diretorioImagem, $diretorioArquivo) {
        $Painel->editorBalao(
            name: 'texto',
            label: 'Texto',
            placeholder: 'Digite seu texto',
            diretorioImagem: $diretorioImagem,
            diretorioArquivo: $diretorioArquivo,
            obrigatorio: true,
            // @codingStandardsIgnoreStart
            bar: 'bold,italic,underline,Strikethrough,fwDestaque,|,fontColor,|,alignment,|,link,removeFormat,|,insertTable,fwImagem,fwArquivo,mediaEmbed,|,horizontalLine,FwObservacao,|,numberedList,bulletedList',
            // @codingStandardsIgnoreEnd
            barBalao: 'bold,italic,underline,Strikethrough,fwDestaque,|,fontColor,|,link,removeFormat'
        );
    });
});

return $Painel;
