<?php

namespace Tests\Api;

use Tests\Token\Clube;
use App\Classes\ParceiroCupom\Status;

class ParceiroCupomTest extends Clube
{
    private string $idCupom;

    public function __construct()
    {
        parent::__construct();
        $this->pegarToken();
    }

    public function __destruct()
    {
        $this
            ->tabela(TABELA_PARCEIRO_CUPOM)
            ->resetar();
    }

    public function listarCuponsTest(): ParceiroCupomTest
    {
        $dado = $this
            ->Curl
            ->json([
                'pagina' => 1
            ])
            ->get('/parceiro-cupom')
            ->array();

        $this->idCupom = $dado['dado']['lista'][0]['id'] ?? 'sem-id';

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.lista');
    }

    public function buscarCupomTest(): ParceiroCupomTest
    {
        $dado = $this
            ->Curl
            ->get('/parceiro-cupom/' . $this->idCupom)
            ->array();

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.id')
            ->checkIndiceExiste('dado.status');
    }

    public function atualizarStatusTest(): ParceiroCupomTest
    {
        $this
            ->Curl
            ->body([
                'status' => Status::CANCELADO
            ])
            ->put('/parceiro-cupom/' . $this->idCupom);

        return $this
            ->checkStatus(204);
    }

    public function verificarSeStatusMudouTest(): ParceiroCupomTest
    {
        $this
            ->Curl
            ->get('/parceiro-cupom/' . $this->idCupom);

        return $this
            ->checkIndiceIgual('dado.status', Status::CANCELADO);
    }
}
