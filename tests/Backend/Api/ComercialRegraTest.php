<?php

namespace Tests\Api;

use Tests\Token\Clube;

class ComercialRegraTest extends Clube
{
    private string $idRegra;

    public function __construct()
    {
        $this->pegarToken();
        parent::__construct();
    }

    private function getBody(array $array = []): array
    {
        return array_merge([
            'titulo'  => nomeCompletoAleatorio(),
            'texto'   => 'Texto de teste 123',
            'empresa' => [
                '14afa776394ada4be23be6acf7e3259e'
            ]
        ], $array);
    }

    public function listarTodosTest(): ComercialRegraTest
    {
        $this
            ->Curl
            ->json([
                'pagina' => 1
            ])
            ->get('/comercial-regra');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista');
    }

    public function salvarNovaRegraTest(): ComercialRegraTest
    {
        $dado = $this
            ->Curl
            ->body($this->getBody())
            ->post('/comercial-regra')
            ->array();

        $this->idRegra = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }

    public function naoPodeSalvarComEmpresaInvalidaTest(): ComercialRegraTest
    {
        $this
            ->Curl
            ->body($this->getBody([
                'empresa' => [
                    '14afa776394ada4be23be6acf7e3259k',
                ]
            ]))
            ->post('/comercial-regra');

        return $this
            ->checkStatus(400)
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', "Campo 'id_comercial_empresa' não contém um valor padrão.");
    }

    public function atualizarRegraTest(): ComercialRegraTest
    {
        $this
            ->Curl
            ->body($this->getBody())
            ->put("/comercial-regra/{$this->idRegra}");

        return $this
            ->checkStatus(204);
    }

    public function deletarRegraTest(): ComercialRegraTest
    {
        $this
            ->Curl
            ->delete("/comercial-regra/{$this->idRegra}");

        return $this
            ->checkStatus(204);
    }
}
