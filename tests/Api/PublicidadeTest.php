<?php

namespace Tests\Api;

use Erro\Excecao;
use Tests\Tests;

class PublicidadeTest extends Tests
{
    private string $idPublicidade = '10dbac1a-bac5-4de0-aba7-f74803aabbc3';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return PublicidadeTest
     * @throws Excecao
     */
    public function buscarPublicidadeTest(): PublicidadeTest
    {
        $this->api('publicidade:buscar');
        $this
            ->Curl
            ->get('/publicidade/' . $this->idPublicidade);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('dado.id', $this->idPublicidade);
    }

    /**
     * @return PublicidadeTest
     * @throws Excecao
     */
    public function listarPublicidadesTest(): PublicidadeTest
    {
        $this->api('publicidade:listar');
        $this
            ->Curl
            ->json([
                'pagina'           => 1,
                'tipo'             => '',
                'status'           => '',
                'data_criacao_de'  => '',
                'data_criacao_ate' => ''
            ])
            ->get('/publicidade');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista');
    }
}
