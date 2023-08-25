<?php

namespace Tests\Api;

use Tests\Api\Token\Clube;

class ParceiroFavoritoTest extends Clube
{
    private string $idParceiro;

    public function __construct()
    {
        parent::__construct();
        $this->getIdParceiro();
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
        $this->api('parceiro_loja:buscar');
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
