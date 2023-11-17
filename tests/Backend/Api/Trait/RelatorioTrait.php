<?php

namespace Tests\Api\Trait;

trait RelatorioTrait
{
    private string $id1 = '14afa776394ada4be23be6acf7e3259e';
    private string $id2 = '0ffc5c56b99f81ca0edea8bdf524b688';

    private function getBody(string|null $id = null)
    {
        return [
            'de'      => dataRemover(hoje(), 7, 'dias'),
            'ate'     => hoje(),
            'empresa' => $id,
        ];
    }

    private function fazerRequest(
        string|null $id = null,
        array|null $body = null
    ): array {
        if (!$body) {
            $body = $this->getBody($id);
        }
        $dado = $this
            ->Curl
            ->loginPainel()
            ->json($body)
            ->get($this->uri)
            ->array()['dado'] ?? [];

        $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');

        return $dado;
    }

    private function pegarSoma($key, $crypto = false)
    {
        $soma = [];

        foreach ($this->dadoEmpresa1 as $item) {
            $dispositivo = $item[$key];
            if ($crypto) {
                $dispositivo = $this->cryptDecode($dispositivo);
            }
            $soma[$dispositivo] = $item['total'];
        }

        foreach ($this->dadoEmpresa2 as $item) {
            $dispositivo = $item[$key];
            if ($crypto) {
                $dispositivo = $this->cryptDecode($dispositivo);
            }
            if (isset($soma[$dispositivo])) {
                $soma[$dispositivo] += $item['total'];
            } else {
                $soma[$dispositivo] = $item['total'];
            }
        }

        return $soma;
    }

    private function buscarSemEmpresa()
    {
        $body = $this->getBody();
        $body['empresa'] = '';

        $this
            ->Curl
            ->loginPainel()
            ->json($body)
            ->get($this->uri);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }
}
