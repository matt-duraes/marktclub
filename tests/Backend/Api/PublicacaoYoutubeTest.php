<?php

namespace Tests\Api;

use Tests\Tests;

final class PublicacaoYoutubeTest extends Tests
{
    protected string $scope = 'publicacao_youtube';
    protected string $uri = '/publicacao-youtube';
    public string $automatico = 'lbsad';

    public function __construct()
    {
        $this
            ->tabela(TABELA_PUBLICACAO_YOUTUBE)
            ->resetar();
        parent::__construct();
    }

    protected function pegarBody()
    {
        return [];
    }
}

