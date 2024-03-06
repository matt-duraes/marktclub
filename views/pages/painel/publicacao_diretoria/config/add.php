<?php

use App\Classes\Geral\Status;
use App\Classes\PublicacaoDiretoria\Grupo;

$Painel = new PainelConfig\Add(app: 'publicidade_diretoria', acao: $acao);
$diretorioImagem = sessao('PAINEL.upload_grupo')['imagem'] ?? '';
$diretorioArquivo = sessao('PAINEL.upload_grupo')['arquivo'] ?? '';

$Painel->coluna(callback: function () use ($Painel, $diretorioImagem) {
    $Painel->fieldset('Imagem', function () use ($Painel, $diretorioImagem) {
        $Painel->imagem(name: 'imagem', diretorio: $diretorioImagem);
    });
    $Painel->fieldset('Dados principais', function () use ($Painel, $diretorioImagem) {
        $Painel
            ->input(
                name: 'nome',
                label: 'Nome',
                placeholder: 'Digite um nome',
                contador: 80,
                obrigatorio: true
            )
            ->input(name: 'cargo', label: 'Cargo', placeholder: 'Digite um cargo', contador: 100)
            ->select(
                name: 'grupo',
                label: 'Grupo',
                placeholder: 'Escolha um grupo',
                lista: (new Grupo())->select('Escolha uma opção')
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
