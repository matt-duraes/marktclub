<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Add(app: 'votacao', acao: $acao);
$diretorioImagem = sessao('PAINEL.upload_grupo')['imagem'] ?? '';
$diretorioArquivo = sessao('PAINEL.upload_grupo')['arquivo'] ?? '';

$Painel->coluna(callback: function () use ($Painel, $diretorioArquivo, $diretorioImagem) {
    $Painel->fieldset('Dados principais', function () use ($Painel, $diretorioArquivo, $diretorioImagem) {
        $Painel
            ->input(
                name: 'titulo',
                label: 'Título',
                placeholder: 'Digite um título',
                contador: 250,
                obrigatorio: true
            )
            ->editorBalao(
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

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados de publicação', function () use ($Painel) {
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
                obrigatorio: true
            );
    });
    $Painel->fieldset('Outros dados', function () use ($Painel) {
        $Painel
            ->switch(name: 'voto_unico', label: 'Só podera ter um voto por usuário?')
            ->switch(name: 'identificar_usuario', label: 'Pode identicar o voto do usuário?')
            ->select(
                name: 'status',
                label: 'Status',
                placeholder: 'Escolha um status',
                lista: (new Status())->select('Escolha uma opção')
            );
    });
});

return $Painel;
