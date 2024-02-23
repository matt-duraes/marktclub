<?php

namespace Tests\Api;

use Tests\Tests;

final class AlbumDadoTest extends Tests
{
    protected string $scope = 'album_dado';
    protected string $uri = '/album-dado';
    public string $automatico = 'lbsad';

    public function __construct()
    {
        $this
            ->tabela(TABELA_ALBUM_DADO)
            ->resetar();
        parent::__construct();
    }

    protected function pegarBody()
    {
        return [];
    }
}
