<?php

namespace Tests\Api;

use Tests\Tests;

final class PublicacaoArquivoTest extends Tests
{
    protected string $scope = 'publicacao_arquivo';
    protected string $uri = '/publicacao-arquivo';
    public string $automatico = 'lbsad';

    public function __construct()
    {
        $this
            ->tabela(TABELA_PUBLICACAO_ARQUIVO)
            ->resetar();
        parent::__construct();
    }

    protected function pegarBody()
    {
        return [];
    }
}
