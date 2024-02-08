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
        return [
            'titulo'             => nomeCompletoAleatorio(),
            'texto'              => '<p>Texto aleatório</p>',
            'header_titulo'      => '',
            'header_descricao'   => '',
            'header_tag'         => [],
            'video'              => 'https://www.youtube.com/watch?v=8SbUC-UaAxE',
            'permissao_site'     => 'sim',
            'permissao_restrita' => '',
            'local'              => '',
            'data_inicio'        => agora(),
            'data_final'         => '',
            'status'             => 'ativo'
        ];
    }
}
