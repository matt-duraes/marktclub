<?php

namespace Tests\Api;

use Tests\Tests;

final class AnalyticsNavegadorTest extends Tests
{
    private string $uri = '/relatorio/navegador';
    private string $id1 = '14afa776394ada4be23be6acf7e3259e';
    private string $id2 = '0ffc5c56b99f81ca0edea8bdf524b688';
    private array $dadoEmpresa1;
    private array $dadoEmpresa2;

    public function __construct()
    {
        parent::__construct();
        $this->de = dataRemover(hoje(), 7, 'dias');
        $this->ate = hoje();
        $this->api('relatorio_acesso:listar');
    }

    private function getBody($empresa = 1)
    {
        return [
            'de'      => dataRemover(hoje(), 7, 'dias'),
            'ate'     => hoje(),
            'empresa' => $empresa == 1 ? $this->id1 : $this->id2,
        ];
    }

    private function fazerRequest(
        int $empresa = 1,
        array|null $body = null
    ): array {
        if (!$body) {
            $body = $this->getBody(empresa: $empresa);
        }
        $dado = $this
            ->Curl
            ->json($body)
            ->get($this->uri)
            ->array()['dado'] ?? [];

        $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');

        return $dado;
    }

    public function buscarSemEmpresaTest()
    {
        $body = $this->getBody();
        $body['empresa'] = '';

        $this
            ->Curl
            ->json($body)
            ->get('/relatorio/acesso-dia');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function buscarPrimeiraEmpresaTest()
    {
        $this->dadoEmpresa1 = $this->fazerRequest(empresa: 1);
        return $this;
    }

    public function buscarSegundaEmpresaTest()
    {
        $this->dadoEmpresa2 = $this->fazerRequest(empresa: 2);
        return $this;
    }

    public function buscarAsDuasEmpresasTest()
    {
        $body = $this->getBody();
        $body['empresa'] = [
            $this->id1,
            $this->id2,
        ];
        $dado = $this->fazerRequest(body: $body);

        foreach ($dado as $k => $d) {
            $somaTotalEmpresas = $this->dadoEmpresa1[$k]['total'] + $this->dadoEmpresa2[$k]['total'];
            $this->checkIgual($d['total'], $somaTotalEmpresas);
        }

        return $this;
    }
}
