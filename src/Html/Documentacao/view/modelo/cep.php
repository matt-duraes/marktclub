<?php

$Doc = new DocumentacaoConfig\Fw('MODELO DE CEP', 'Modelo para gerar um CEP.');

echo $Doc
    ->funcao(ROOT . '/src/Modules/EnderecoCep.php')
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
