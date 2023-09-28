<?php

namespace Tests\Api;

use Tests\Token\Clube;

class ParceiroFavoritoTest extends Clube
{
    private string $idParceiro;

    public function __construct()
    {
        $this->pegarToken();
        parent::__construct();
        $this->getIdParceiro();
    }

    public function adicionarFavoritoTest(): ParceiroFavoritoTest
    {
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
        $dado = $this
            ->Curl
            ->json([
                'pagina'     => 1,
                'quantidade' => 100,
                'favorito'   => 'sim',
            ])
            ->get('/parceiro-loja')
            ->array()['dado']['lista'] ?? [];

        foreach ($dado as $i => $d) {
            if ($d['id'] == $this->idParceiro) {
                $this
                    ->checkIndiceIgual('dado.lista.' . $i . '.favorito', 'sim');
            }
        }

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado.lista');
    }

    public function deletaFavoritoTest(): ParceiroFavoritoTest
    {
        $this
            ->Curl
            ->delete('/parceiro-favorito/' . $this->idParceiro);

        return $this
            ->checkStatus(204);
    }

    public function verificaSeDeletouFavoritoTest(): ParceiroFavoritoTest
    {
        $dado = $this
            ->Curl
            ->json([
                'pagina'     => 1,
                'quantidade' => 100,
                'favorito'   => 'sim',
            ])
            ->get('/parceiro-loja')
            ->array()['dado']['lista'] ?? [];

        foreach ($dado as $i => $d) {
            if ($d['id'] == $this->idParceiro) {
                $this
                    ->checkIndiceDiferente('dado.lista.' . $i . '.favorito', 'sim');
            }
        }

        return $this
            ->checkStatus(200);
    }

    private function getIdParceiro(): void
    {
        $dado = $this
            ->Curl
            ->json([
                'pagina'   => 1,
                'favorito' => 'nao'
            ])
            ->get('/parceiro-loja')
            ->array()['dado']['lista'] ?? [];

        $arrayParceiros = [];
        foreach ($dado as $d) {
            if (!empty($d['id'])) {
                $arrayParceiros[] = $d['id'];
            }
        }

        $this->idParceiro = !empty($arrayParceiros) ? valorAleatorio($arrayParceiros) : 'sem-id';
    }
}
