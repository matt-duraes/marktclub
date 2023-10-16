<?php

namespace Tests\Api;

use Erro\Excecao;
use Tests\Token\Clube;

class CarterinhaTest extends Clube
{
    private ?string $idUsuario = '5595203c-f7b1-4211-9981-bf09eb236b35';

    /**
     * @return CarterinhaTest
     * @throws Excecao
     */
    public function buscarCarteirinhaTest(): CarterinhaTest
    {
        $this->api('carteirinha:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/carteirinha/' . $this->idUsuario);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkNaoVazio('dado')
            ->checkIndiceIgual('status', 'sucesso');
    }
}
