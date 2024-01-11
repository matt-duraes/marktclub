<?php

namespace Tests\Api;

use Tests\Tests;

class ComercialRegraTest extends Tests
{
    protected string $scope = 'comercial_regra';
    protected string $uri = '/comercial-regra';
    public string $automatico = 'crd';

    public function naoPodeSalvarComEmpresaInvalidaTest(): ComercialRegraTest
    {
        $this
            ->Curl
            ->loginPainel()
            ->body($this->pegarBody([
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

    protected function pegarBody(array $array = []): array
    {
        return array_merge([
            'titulo'  => nomeCompletoAleatorio(),
            'texto'   => 'Texto de teste 123',
            'empresa' => [
                '14afa776394ada4be23be6acf7e3259e'
            ]
        ], $array);
    }
}
