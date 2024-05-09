<?php

use App\Classes\Geral\Status;

$diretorioImagem = sessao('PAINEL.upload_grupo')['imagem'] ?? '';
$diretorioArquivo = sessao('PAINEL.upload_grupo')['arquivo'] ?? '';

$Painel = new PainelConfig\Add(app: 'publicidade_home', acao: $acao);
$Painel->coluna(callback: function () use ($Painel, $diretorioImagem, $diretorioArquivo) {
    $Painel->fieldset('Dados principais', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo_interno', label: 'Título para o painel', placeholder: 'Título para o painel', obrigatorio: true)
            ->input(name: 'titulo', label: 'Título do site', placeholder: 'Título para o site', obrigatorio: true)
            ->url(name: 'link', label: 'Link da live', placeholder: 'Link da live', obrigatorio: true)
            ->switch(name: 'link_restrito', label: 'Link disponível apenas na área restrita?');
    });
    $Painel->fieldset('Permissões', function () use ($Painel) {
        $Painel
            ->dataHora(
                name: 'data_inicio',
                label: 'Data de publição',
                placeholder: 'Digite a data de publicação',
                obrigatorio: true
            )
            ->dataHora(
                name: 'data_final',
                label: 'Data de remoção',
                placeholder: 'Digite a data de remoção',
                ajuda: 'Colocar uma data caso queira que essa notícia saia do site no dia e hora desejado.',
                obrigatorio: true
            )
            ->switch(name: 'permissao_restrita', label: 'Aparecer na área restrita')
            ->switch(name: 'permissao_site', label: 'Aparecer no site')
            ->select(
                name: 'status',
                label: 'Status',
                placeholder: 'Escolha um status',
                lista: (new Status())->select('Escolha uma opção'),
                obrigatorio: true
            );
    });
});

$Painel->coluna(callback: function () use ($Painel, $diretorioImagem, $diretorioArquivo) {
    $Painel->coluna(callback: function () use ($Painel, $diretorioImagem) {
        $Painel->fieldset('Imagem grande', function () use ($Painel, $diretorioImagem) {
            $Painel->imagem(name: 'imagem_site', diretorio: $diretorioImagem);
        });
        $Painel->fieldset('Imagem pequena', function () use ($Painel, $diretorioImagem) {
            $Painel->imagem(name: 'imagem_restrito', diretorio: $diretorioImagem);
        });
    });
});

$Painel->coluna(callback: function () use ($Painel, $diretorioImagem, $diretorioArquivo) {
    $Painel->fieldset('Texto', function () use ($Painel, $diretorioImagem, $diretorioArquivo) {
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
