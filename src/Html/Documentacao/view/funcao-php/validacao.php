<?php

$Doc = new DocumentacaoConfig\Fw('FUNÇÕES DE VALIDAÇÃO', 'Valida os campos informados.');
echo $Doc
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('validarJson')
            ->descricao('Valida se string é um json')
            ->blocoParametro(function () use ($Doc) {
                $Doc
                    ->parametro('null|string', '$json', 'Json a ser validado');
            })
            ->codigo('validarJson(?string $json): bool')
            ->retorno('bool', 'Retorna true caso o valor seja válido')
            ->blocoExemplo(function () use ($Doc) {
                $Doc
                    ->exemploPhp('echo validarJson(\'["campo_1", "campo_2"]\')', 'validarJson', ['["campo_1", "campo_2"]'])
                    ->exemploPhp('echo validarJson(\'{"campo_1", "campo_2"}\')', 'validarJson', ['{"campo_1", "campo_2"}'])
                    ->exemploPhp('echo validarJson("string_aqui")', 'validarJson', ['string_aqui']);
            });
    })
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('validarUrl')
            ->descricao('Valida se é uma URL')
            ->blocoParametro(function () use ($Doc) {
                $Doc
                    ->parametro('null|string', '$url', 'Url a ser validada');
            })
            ->codigo('validarJson(?string $url): bool')
            ->retorno('bool', 'Retorna true caso o valor seja válido')
            ->blocoExemplo(function () use ($Doc) {
                $Doc
                    ->exemploPhp('echo validarUrl("https://google.com")', 'validarUrl', ['https://google.com'])
                    ->exemploPhp('echo validarUrl("http://google.com")', 'validarUrl', ['http://google.com'])
                    ->exemploPhp('echo validarUrl("https://google.com.br")', 'validarUrl', ['https://google.com.br'])
                    ->exemploPhp('echo validarUrl("https://google")', 'validarUrl', ['https://google'])
                    ->exemploPhp('echo validarUrl("google.com")', 'validarUrl', ['google.com'])
                    ->exemploPhp('echo validarUrl("google.com")', 'validarUrl', ['google.com']);
            });
    })
    ->funcao(ROOT . '/src/Function/Validar.func.php');
