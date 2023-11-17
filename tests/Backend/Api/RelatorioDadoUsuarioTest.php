<?php

namespace Tests\Api;

use Tests\Api\Trait\RelatorioTrait;
use Tests\Tests;

final class RelatorioDadoUsuarioTest extends Tests
{
    use RelatorioTrait;

    private string $uri = '/relatorio/dado-usuario';
    private array $dadoEmpresa1;
    private array $dadoEmpresa2;

    public function __construct()
    {
        parent::__construct();
        $this->api('relatorio_usuario:listar');
    }

    private function getBody(string|null $id = null)
    {
        return [
            'empresa' => $id,
        ];
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
            $this->checkIgual($d['total'], $somaTotalEmpresas);
        }

        return $this;
    }
}
