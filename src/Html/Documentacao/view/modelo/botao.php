<?php

$Doc = new DocumentacaoConfig\Fw('MODELO DE BOTÃO', 'Modelo de botão, usado para o switch para campos que podem receber apenas os valores 1 para sim e vazio para não.');

echo $Doc
    ->funcao(ROOT . '/src/Modules/Botao.php')
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('vazio')
            ->descricao('Verifica se o valor do modelo é vazio')
            ->codigo('vazio(): bool')
            ->retorno('bool', 'Retorna true caso o modelo for vazio');
    })
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('valido')
            ->descricao('Verifica se o valor do modelo é válido')
            ->codigo('valido(): bool')
            ->retorno('bool', 'Retorna true caso o modelo for valido');
    });
