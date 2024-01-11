<?php

namespace Tests\Api;

use Tests\Api\Trait\RelatorioTrait;
use Tests\Tests;

final class RelatorioLojaVendaTest extends Tests
{
    use RelatorioTrait;

    protected string $uri = '/relatorio/loja-venda';
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
        $somas = $this->somarArrays($this->dadoEmpresa1, $this->dadoEmpresa2);

        foreach ($dado as $key => $items) {
            foreach ($items as $item) {
                if ($key === 'venda_mes') {
                    $data = $item['data'];
                    $ticket = $this->calcularTicket($somas[$key][$data]['valor'], $somas[$key][$data]['venda']);

                    $this->checkIgual($this->converterParaNumero($item['valor']), $somas[$key][$data]['valor']);
                    $this->checkIgual($this->converterParaNumero($item['venda']), $somas[$key][$data]['venda']);
                    $this->checkIgual($this->converterParaNumero($item['ticket']), $ticket);
                } else {
                    $loja = $item['loja'];
                    $total_dado = $this->converterParaNumero($item['total']);
                    $total_soma = $somas[$key][$loja]['total'];

                    $this->checkIgual(round($total_dado), round($total_soma), 'total de ' . $key);
                }
            }
        }

        return $this;
    }

    private function somarArrays($array1, $array2)
    {
        $total = [
            'venda_mes'   => [],
            'venda_loja'  => [],
            'ticket_loja' => []
        ];

        foreach ([$array1, $array2] as $array) {
            foreach ($array as $key => $items) {
                if ($key === 'venda_mes') {
                    foreach ($items as $item) {
                        $data = $item['data'];

                        if (!isset($total[$key][$data])) {
                            $total[$key][$data] = ['valor' => 0, 'ticket' => 0, 'venda' => 0];
                        }

                        $total[$key][$data]['valor'] += $this->converterParaNumero($item['valor']);
                        $total[$key][$data]['venda'] += $this->converterParaNumero($item['venda']);
                    }
                } else {
                    foreach ($items as $item) {
                        $loja = $item['loja'];

                        if (!isset($total[$key][$loja])) {
                            $total[$key][$loja] = ['total' => 0, 'porcentagem' => 0];
                        }

                        $total[$key][$loja]['total'] += $this->converterParaNumero($item['total']);
                        $total[$key][$loja]['porcentagem'] += $this->converterParaNumero($item['porcentagem']);
                    }
                }
            }
        }

        return $total;
    }

    private function converterParaNumero($value)
    {
        return is_numeric($value) ? $value : floatval(str_replace(',', '.', str_replace('.', '', $value)));
    }

    private function calcularTicket($valor, $venda)
    {
        return $venda == 0 || $venda == 0
            ? 0
            : number_format($valor / $venda, 2, '.', '');
    }
}
