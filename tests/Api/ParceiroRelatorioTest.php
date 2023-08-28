<?php

namespace Tests\Api;

use Tests\Api\Token\Clube;

class ParceiroRelatorioTest extends Clube
{
    private string $idRelatorio;

    public function __construct()
    {
        $this->pegarToken();
        parent::__construct();
    }

    public function __destruct()
    {
        $this->tabela(TABELA_ANALYTICS_LOJA_VENDA)->resetar();
    }

    private function getBody(array $array = []): array
    {
        return array_merge([
            'empresa'          => '14afa776394ada4be23be6acf7e3259e',
            'parceiro'         => 'f10e05c0-5b02-4bff-8e22-719a8797f0d6',
            'numero_transacao' => numeroAleatorio(1, 10000),
            'valor_venda'      => numeroAleatorio(1, 10000),
            'data_relatorio'   => dataPassadaAleatorio()
        ], $array);
    }

    public function listarTodosOsRelatoriosTest(): ParceiroRelatorioTest
    {
        $this
            ->Curl
            ->json([
                'pagina' => 1
            ])
            ->get('/parceiro-relatorio');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista');
    }

    public function salvarRelatorioValidoTest(): ParceiroRelatorioTest
    {
        $dado = $this
            ->Curl
            ->body($this->getBody())
            ->post('/parceiro-relatorio')
            ->array();

        $this->idRelatorio = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado.id')
            ->checkIndiceExiste('dado.empresa.id')
            ->checkIndiceExiste('dado.parceiro.id');
    }

    public function naoPodeSalvarComEmpresaInvalidoTest(): ParceiroRelatorioTest
    {
        $this
            ->Curl
            ->body($this->getBody([
                'empresa' => '14afa776394ada4be23be6acf7e32599'
            ]))
            ->post('/parceiro-relatorio');

        return $this
            ->checkStatus(404)
            ->checkIndiceExiste('erro.mensagem')
            ->checkIndiceIgual('erro.mensagem', 'Não foi encontrado uma empresa por esse código.');
    }

    public function atualizarRelatorioTest(): ParceiroRelatorioTest
    {
        $this
            ->Curl
            ->body($this->getBody())
            ->put('/parceiro-relatorio/' . $this->idRelatorio);

        return $this
            ->checkStatus(204);
    }

    public function buscarRelatorioPorIdTest(): ParceiroRelatorioTest
    {
        $this
            ->Curl
            ->get("/parceiro-relatorio/{$this->idRelatorio}");

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado.id')
            ->checkIndiceExiste('dado.empresa.id')
            ->checkIndiceExiste('dado.parceiro.id');
    }

    public function deletarRelatorioTest(): ParceiroRelatorioTest
    {
        $this
            ->Curl
            ->delete("/parceiro-relatorio/{$this->idRelatorio}");

        return $this
            ->checkStatus(204);
    }

    public function naoPodeAcharDeletadoTest(): ParceiroRelatorioTest
    {
        $this
            ->Curl
            ->get("/parceiro-relatorio/{$this->idRelatorio}");

        return $this
            ->checkStatus(404)
            ->checkIndiceExiste('erro.mensagem')
            ->checkIndiceIgual('erro.mensagem', 'Essa página ou recurso não existe ou foi movida para outra URL.');
    }
}
