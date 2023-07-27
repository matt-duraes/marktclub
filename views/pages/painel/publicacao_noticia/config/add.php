<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Add(app: 'publicidade_noticia', acao: $acao);
$diretorioImagem = sessao('PAINEL.upload_grupo')['imagem'] ?? '';
$diretorioArquivo = sessao('PAINEL.upload_grupo')['arquivo'] ?? '';

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados principais', function () use ($Painel) {
        $Painel
            ->input(
                name: 'titulo_grande',
                label: 'Título grande',
                placeholder: 'Digite um título',
                contador: 200,
                obrigatorio: true
            )
            ->input(name: 'subtitulo', label: 'Subtítulo', placeholder: 'Digite um subtítulo', contador: 200);
    });
    $Painel->fieldset('Dados secundários', function () use ($Painel) {
        $Painel
            ->input(
                name: 'titulo_pequeno',
                label: 'Título secundário',
                placeholder: 'Digite um título secundário',
                contador: 80
            )
            ->input(
                name: 'texto_pequeno',
                label: 'Texto pequeno',
                placeholder: 'Digite um texto pequeno',
                contador: 120
            );
    });
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Fonte', function () use ($Painel) {
        $Painel->div(class: 'bloco_row', callback: function () use ($Painel) {
            $Painel
                ->input(name: 'autor_noticia', label: 'Autor da notícia', placeholder: 'Digite um autor', contador: 100)
                ->input(
                    name: 'fonte_noticia',
                    label: 'Fonte da notícia',
                    placeholder: 'Digite uma fonte',
                    contador: 100
                )
                ->url(name: 'fonte_link', label: 'Link da fonte', placeholder: 'Digite um link');
        });
    });
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados de publicação', function () use ($Painel) {
        $Painel
            ->dataHora(
                name: 'data_publicacao_inicio',
                label: 'Data de publição',
                placeholder: 'Digite a data de publicação',
                obrigatorio: true
            )
            ->dataHora(
                name: 'data_publicacao_final',
                label: 'Data de remoção',
                placeholder: 'Digite a data de remoção',
                ajuda: 'Colocar uma data caso queira que essa notícia saia do site no dia e hora desejado.'
            )
            ->dataHora(
                name: 'data_publicacao_atualizacao',
                label: 'Data de atualiação',
                placeholder: 'Digite uma data de atualização',
                ajuda: 'Colocar uma data caso queira que aparece que essa notícia foi atualizada.'
            );
    });
    $Painel->fieldset('Permissões', function () use ($Painel) {
        $Painel
            ->switch(name: 'permissao_restrita', label: 'Aparecer na área restrita')
            ->switch(name: 'permissao_site', label: 'Aparecer no site')
            ->switch(name: 'permissao_banner', label: 'Aparecer no banner')
            ->select(
                name: 'status',
                label: 'status',
                placeholder: 'Escolha uma opção',
                lista: (new Status())->select('Escolha uma opção')
            );
    });
});

$Painel->coluna(callback: function () use ($Painel, $diretorioImagem) {
    $Painel->fieldset('Imagem principais', function () use ($Painel, $diretorioImagem) {
        $Painel->imagem(name: 'imagem_grande', diretorio: $diretorioImagem);
    });
    $Painel->fieldset('Imagem secundária', function () use ($Painel, $diretorioImagem) {
        $Painel->imagem(name: 'imagem_pequena', diretorio: $diretorioImagem);
    });
    $Painel->fieldset('Imagem social', function () use ($Painel, $diretorioImagem) {
        $Painel->imagem(name: 'imagem_social', diretorio: $diretorioImagem);
    });
});

$Painel->coluna(callback: function () use ($Painel, $diretorioImagem, $diretorioArquivo) {
    $Painel->fieldset('Texto principal', function () use ($Painel, $diretorioImagem, $diretorioArquivo) {
        $Painel->editorBalao(
            name: 'texto_grande',
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
