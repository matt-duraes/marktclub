<?php

namespace Tests\Api;

use App\Classes\EnqueteSatisfacao\Atendimento;
use App\Classes\EnqueteSatisfacao\Navegar;
use App\Classes\EnqueteSatisfacao\Procura;
use App\Classes\EnqueteSatisfacao\Suporte;
use Tests\Api\Token\Clube;

class EnqueteSatisfacaoTest extends Clube
{
    private string $idEnquete;

    public function __construct()
    {
        parent::__construct();
        $this->pegarToken();
    }

    public function salvarNovaEnqueteSatisfacaoTest(): EnqueteSatisfacaoTest
    {
        $dado = $this
            ->Curl
            ->body($this->getBody())
            ->post('/enquete-satisfacao')
            ->array();

        $this->idEnquete = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }

    public function buscarEnqueteTest(): EnqueteSatisfacaoTest
    {
        $this
            ->Curl
            ->get('/enquete-satisfacao/' . $this->idEnquete);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.id', $this->idEnquete)
            ->checkIndiceExiste('dado.id');
    }

    public function listarEnquetesTest(): EnqueteSatisfacaoTest
    {
        $this
            ->Curl
            ->json([
                'pagina' => 1
            ])
            ->get('/enquete-satisfacao');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }

    private function getBody(array $array = []): array
    {
        return array_merge([
            'navegar' => valorAleatorio(array_keys((new Navegar())->select())),
            'procura' => valorAleatorio(array_keys((new Procura())->select())),
            'suporte' => valorAleatorio(array_keys((new Suporte())->select())),
            'atendimento' => valorAleatorio(array_keys((new Atendimento())->select())),
            'sistemas' => [1, 2, 3],
            'comentario' => 'Teste de comentário'
        ], $array);
    }
}
