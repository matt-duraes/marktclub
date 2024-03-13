<?php

namespace Tests\Api;

use Tests\Tests;

final class PublicacaoListaTest extends Tests
{
    protected string $scope = 'publicacao_lista';
    protected string $uri = '/publicacao-lista';
    public string $automatico = 'lbsad';

    public function __construct()
    {
        $this
            ->tabela(TABELA_PUBLICACAO_LISTA)
            ->resetar();
        parent::__construct();
    }

    protected function pegarBody()
    {
        return [];
    }
}
