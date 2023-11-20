<?php

namespace Tests\Api;

use Tests\Token\Clube;

class ComercialRestricaoTest extends Clube
{
    public function selectRestricaoTest()
    {
        $this->api('comercial_restricao:listar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/comercial-restricao/select');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado');
    }

    public function selectRestricaoComTituloTest()
    {
        $this->api('comercial_restricao:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'titulo' => 'Selecione...'
            ])
            ->get('/comercial-restricao/select');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('dado.', 'Selecione...');
    }
}
