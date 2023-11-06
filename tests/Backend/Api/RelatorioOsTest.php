<?php

namespace Tests\Api;

use Tests\Api\Trait\AnalyticsTrait;
use Tests\Tests;

final class RelatorioOsTest extends Tests
{
    use AnalyticsTrait;

    private string $uri = '/relatorio/os';
    private array $dadoEmpresa1;
    private array $dadoEmpresa2;

    public function __construct()
    {
        parent::__construct();
        $this->api('relatorio_acesso:listar');
    }

    public function buscarSemEmpresaTest()
    {
        return $this->buscarSemEmpresa();
    }

    public function buscarPrimeiraEmpresaTest()
    {
        $this->dadoEmpresa1 = $this->fazerRequest($this->id1);
        return $this;
    }

    public function buscarSegundaEmpresaTest()
    {
        $this->dadoEmpresa2 = $this->fazerRequest($this->id2);
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
        $dispositivosSoma = $this->pegarSoma('os');

        foreach ($dado as $k => $d) {
            $this->checkIgual($d['total'], $dispositivosSoma[$d['os']]);
        }

        return $this;
    }
}
