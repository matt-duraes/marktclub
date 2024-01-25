<?php

namespace Tests\Api;

use Tests\Tests;

class ParceiroRelatorioTest extends Tests
{
    protected string $scope = 'parceiro_relatorio';
    protected string $uri = '/parceiro-relatorio';
    public string $automatico = 'lbsad';

    public function __destruct()
    {
        $this->tabela(TABELA_ANALYTICS_LOJA_VENDA)->resetar();
    }

    public function naoPodeSalvarComEmpresaInvalidoTest(): ParceiroRelatorioTest
    {
        $this->api('parceiro_relatorio:salvar');
        $this
            ->Curl
            ->body($this->pegarBody([
                'empresa' => '14afa776394ada4be23be6acf7e32599'
            ]))
            ->post('/parceiro-relatorio');

        return $this
            ->checkStatus(404)
            ->checkIndiceExiste('erro.mensagem')
            ->checkIndiceIgual('erro.mensagem', 'Não foi encontrado uma empresa por esse código.');
    }

    protected function pegarBody(array $array = []): array
    {
        return array_merge([
            'numero_transacao' => numeroAleatorio(1, 10000),
            'empresa'          => '14afa776394ada4be23be6acf7e3259e',
            'parceiro'         => '9792e058562303f9e7e0604c5117c569',
            'valor_venda'      => numeroAleatorio(1, 10000),
            'data_relatorio'   => dataPassadaAleatorio()
        ], $array);
    }
}
