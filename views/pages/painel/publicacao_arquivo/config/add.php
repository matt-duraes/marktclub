<?php

use App\Classes\Geral\Status;
use App\Classes\PublicacaoArquivo\Tipo;

$Painel = new PainelConfig\Add(app: 'publicidade_arquivo', acao: $acao);
$diretorioImagem = sessao('PAINEL.upload_grupo')['imagem'] ?? '';
$diretorioArquivo = sessao('PAINEL.upload_grupo')['arquivo'] ?? '';

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados principais', function () use ($Painel) {
        $Painel
            ->input(
                name: 'titulo',
                label: 'Título',
                placeholder: 'Digite um título',
                contador: 191,
                obrigatorio: true
            )
            ->input(name: 'texto', label: 'Texto', placeholder: 'Digite um texto', contador: 250, obrigatorio: true)
            ->select(
                name: 'tipo',
                label: 'Tipo',
                placeholder: 'Tipo de arquivo',
                obrigatorio: true,
                lista: (new Tipo())->select('Escolha uma opção')
            );
    });
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
                ajuda: 'Colocar uma data caso queira que essa notícia saia do site no dia e hora desejado.'
            );
    });
    $Painel->fieldset('Permissões', function () use ($Painel) {
        $Painel
            ->switch(name: 'permissao_restrita', label: 'Aparecer na área restrita')
            ->switch(name: 'permissao_site', label: 'Aparecer no site')
            ->select(
                name: 'status',
                label: 'Status',
                placeholder: 'Escolha um status',
                lista: (new Status())->select('Escolha uma opção')
            );
    });
});
$Painel->coluna(callback: function () use ($Painel, $diretorioImagem, $diretorioArquivo) {
    $Painel->fieldset('Imagem', function () use ($Painel, $diretorioImagem) {
        $Painel->imagem(name: 'imagem', diretorio: $diretorioImagem);
    });
    $Painel->fieldset('Arquivo', function () use ($Painel, $diretorioArquivo) {
        $Painel->imagem(name: 'arquivo', diretorio: $diretorioArquivo);
    });
});

return $Painel;
