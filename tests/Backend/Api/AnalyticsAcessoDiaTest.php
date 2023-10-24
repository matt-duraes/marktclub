<?php

namespace Tests\Api;

use Tests\Api\Trait\AnalyticsTrait;
use Tests\Tests;

final class AnalyticsAcessoDiaTest extends Tests
{
    use AnalyticsTrait;

    private string $uri = '/relatorio/acesso-dia';
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

        foreach ($dado as $k => $d) {
            $somaTotalEmpresas = $this->dadoEmpresa1[$k]['total'] + $this->dadoEmpresa2[$k]['total'];
            $somaUnicoEmpresas = $this->dadoEmpresa1[$k]['unico'] + $this->dadoEmpresa2[$k]['unico'];

            $this->checkIgual($d['total'], $somaTotalEmpresas);
            $this->checkIgual($d['unico'], $somaUnicoEmpresas);
        }

        return $this;
    }
}
