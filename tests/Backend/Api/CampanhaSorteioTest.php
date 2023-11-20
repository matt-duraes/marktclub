<?php

namespace Tests\Api;

use Tests\Token\Clube;

class CampanhaSorteioTest extends Clube
{
    private string $id = '5f7fe450-4705-4aff-aeb1-242e2ca8d3ce';
    private string $hash;

    public function __construct()
    {
        parent::__construct();
        $this->tabela(TABELA_CAMPANHA_SORTEIO)->resetar();
    }

    public function buscarSorteioTest()
    {
        $this->api('campanha_sorteio:buscar');
        $this
            ->Curl
            ->get('/campanha-sorteio/' . $this->id);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('dado.id', $this->id)
            ->checkIndiceExiste('dado.titulo')
            ->checkIndiceExiste('dado.texto')
            ->checkIndiceExiste('dado.imagem');
    }

    public function resultadoSorteioTest()
    {
        $this->api('campanha_sorteio:sortear');
        $dado = $this
            ->Curl
            ->body([
                'id' => $this->id,
            ])
            ->post('/campanha-sorteio/resultado')
            ->array();

        $this->hash = $dado['dado']['hash'] ?? 'sem-hash';

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.sorteados')
            ->checkNaoVazio('dado.sorteados')
            ->checkIndiceExiste('dado.hash');
    }

    public function buscarResultadoTest()
    {
        $this->api('campanha_sorteio:resultado');
        $this
            ->Curl
            ->get('/campanha-sorteio/resultado/' . $this->id . '/' . $this->hash);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.id')
            ->checkIndiceExiste('dado.titulo')
            ->checkIndiceExiste('dado.texto')
            ->checkIndiceExiste('dado.imagem');
    }
}
