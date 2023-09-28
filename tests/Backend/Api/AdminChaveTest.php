<?php

namespace Tests\Api;

use Tests\Tests;

final class AdminChaveTest extends Tests
{
    public function __construct()
    {
        parent::__construct();
        $this->api('admin:chave_publica admin:chave_privada');
    }

    public function pegarChavePublicaTest()
    {
        $this
            ->Curl
            ->get('/admin/chave-publica');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function pegarChavePrivadaTest()
    {
        $this
            ->Curl
            ->get('/admin/chave-privada');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }
}
