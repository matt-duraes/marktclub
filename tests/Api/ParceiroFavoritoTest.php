<?php

namespace Tests\Api;

use Tests\Api\Token\Clube;

class ParceiroFavoritoTest extends Clube
{
    private string $idParceiro;

    public function __construct()
    {
        $this->api('parceiro_loja:buscar');
        $dado = $this
            ->Curl
            ->json([
                'pagina' => 1,
            ])
            ->get('/parceiro-loja')
            ->array()['dado'];

        $arrayParceiros = [];
        foreach ($dado['lista'] as $d) {
            $arrayParceiros[] = $d['id'] ?? '';
        }

        $this->idParceiro = valorAleatorio($arrayParceiros);
    }

    public function adicionarFavoritoTest(): ParceiroFavoritoTest
    {
        $this->api('parceiro_favorito:salvar');
        $this
            ->Curl
            ->body([
                'parceiro' => $this->idParceiro
            ])
            ->post('/parceiro-favorito');

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado.id');
    }

    public function verificarSeFavoritouTest(): ParceiroFavoritoTest
    {
        $this->api('parceiro_loja:buscar');
        $this
            ->Curl
            ->get('/parceiro-loja/' . $this->idParceiro);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado.favorito')
            ->checkIndiceIgual('dado.favorito', 'sim');
    }

    public function deletaFavoritoTest(): ParceiroFavoritoTest
    {
        $this->api('parceiro_favorito:deletar');
        $this
            ->Curl
            ->delete('/parceiro-favorito/' . $this->idParceiro);

        return $this
            ->checkStatus(204);
    }

    public function verificaSeDeletouFavoritoTest(): ParceiroFavoritoTest
    {
        $this->api('parceiro_loja:buscar');
        $this
            ->Curl
            ->get('/parceiro-loja/' . $this->idParceiro);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado.favorito')
            ->checkIndiceIgual('dado.favorito', 'nao');
    }
}
