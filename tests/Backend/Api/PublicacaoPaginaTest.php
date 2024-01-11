<?php

namespace Tests\Api;

use Tests\Tests;

class PublicacaoPaginaTest extends Tests
{
    protected string $scope = 'publicacao_pagina';
    protected string $uri = '/publicacao-pagina';
    public string $automatico = 'ru';
    public bool $automaticoPainel = true;

    protected function pegarBody(array $array = []): array
    {
        return array_merge([
            'titulo'           => 'Título da página',
            'texto'            => 'Texto da página',
            'header_titulo'    => '',
            'header_descricao' => '',
            'header_tag'       => [1, 2],
        ], $array);
    }
}
