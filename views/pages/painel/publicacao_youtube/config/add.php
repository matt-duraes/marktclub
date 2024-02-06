<?php

use App\Classes\Geral\Status;
use App\Classes\PublicacaoYoutube\Local;

$Painel = new PainelConfig\Add(app: 'publicacao_youtube', acao: $acao);
$diretorioImagem = sessao('PAINEL.upload_grupo')['imagem'] ?? '';
$diretorioArquivo = sessao('PAINEL.upload_grupo')['arquivo'] ?? '';

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados principais', function () use ($Painel) {
        $Painel
            ->input(
                name: 'titulo',
                label: 'Título',
                placeholder: 'Digite um título',
                contador: 200,
                obrigatorio: true
            )
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
                ajuda: 'Colocar uma data caso queira que essa notícia saia do site no dia e hora desejado.'
            )
            ->url(name: 'video', label: 'Link do YouTube', placeholder: 'Digite o link do youtube');
    });
    $Painel->fieldset('SEO', function () use ($Painel) {
        $Painel
            ->input(
                name: 'header_titulo',
                label: 'Título',
                placeholder: 'Digite um título',
                contador: 65
            )
            ->input(name: 'header_descricao', label: 'Descrição', placeholder: 'Digite uma descrição', contador: 155)
            ->tag(name: 'header_tag', label: 'Tags', placeholder: 'Digite sua tags', tipo: 'texto', espaco: true);
    });
    $Painel->fieldset('Permissões', function () use ($Painel) {
        $Painel
            ->switch(name: 'permissao_restrita', label: 'Aparecer na área restrita')
            ->switch(name: 'permissao_site', label: 'Aparecer no site')
            ->select(
                name: 'local',
                label: 'Local',
                placeholder: 'Escolha um local',
                lista: (new Local())->select('Escolha uma opção')
            )
            ->select(
                name: 'status',
                label: 'Status',
                placeholder: 'Escolha um status',
                lista: (new Status())->select('Escolha uma opção')
            );
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
