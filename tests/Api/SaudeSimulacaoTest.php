<?php

namespace Tests\Api;

use Erro\Excecao;
use Modules\Data;
use Tests\Tests;

final class SaudeSimulacaoTest extends Tests
{
    private string $idSimulacao = '32dd2783-daf2-4cf4-be78-6f43e3f801c5';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return SaudeSimulacaoTest
     * @throws Excecao
     */
    public function buscarSimulacaoTest(): SaudeSimulacaoTest
    {
        $this->api('saude_simulacao:buscar');
        $this
            ->Curl
            ->get('/saude/simulacao/' . $this->idSimulacao);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('dado.id', $this->idSimulacao);
    }

    /**
     * @return SaudeSimulacaoTest
     * @throws Excecao
     */
    public function realizarSimulacaoAmilTest(): SaudeSimulacaoTest
    {
        $titular = (new Data($this->dataPassada()))->data();

        $this->api('saude_simulacao:salvar');
        $this
            ->Curl
            ->body([
                'titular'     => $titular,
                'dependentes' => jsonEncode($this->gerarDependentes()),
                'operadora'   => 'amil',
                'regiao'      => 'sao_paulo',
                'plano'       => 'amil_s80qc',
                'acomodacao'  => ''
            ])
            ->post('/saude/simulacao');

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }

    /**
     * @return array
     */
    private function gerarDependentes(): array
    {
        $dependentes = [];
        for ($i = 0; $i < 4; $i++) {
            $dependentes[] = (new Data($this->dataPassada()))->data();
        }
        return $dependentes;
    }

    /**
     * @return SaudeSimulacaoTest
     * @throws Excecao
     */
    public function realizarSimulacaoUnimedFloripaTest(): SaudeSimulacaoTest
    {
        $titular = (new Data($this->dataPassada()))->data();

        $this->api('saude_simulacao:salvar');
        $this
            ->Curl
            ->body([
                'titular'     => $titular,
                'dependentes' => jsonEncode($this->gerarDependentes()),
                'operadora'   => 'unimed_florianopolis',
                'regiao'      => '',
                'plano'       => 'regional',
                'acomodacao'  => 'enfermaria-30'
            ])
            ->post('/saude/simulacao');

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }

    /**
     * @return SaudeSimulacaoTest
     * @throws Excecao
     */
    public function realizarSimulacaoUnimedSeguroTest(): SaudeSimulacaoTest
    {
        $titular = (new Data($this->dataPassada()))->data();

        $this->api('saude_simulacao:salvar');
        $this
            ->Curl
            ->body([
                'titular'     => $titular,
                'dependentes' => jsonEncode($this->gerarDependentes()),
                'operadora'   => 'unimed_seguro',
                'regiao'      => '',
                'plano'       => '',
                'acomodacao'  => $this->random(['basico', 'versatil', 'pratico'])
            ])
            ->post('/saude/simulacao');

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }

    /**
     * @return SaudeSimulacaoTest
     * @throws Excecao
     */
    public function realizarSimulacaoUnimedTest(): SaudeSimulacaoTest
    {
        $titular = (new Data($this->dataPassada()))->data();

        $this->api('saude_simulacao:salvar');
        $this
            ->Curl
            ->body([
                'titular'     => $titular,
                'dependentes' => jsonEncode($this->gerarDependentes()),
                'operadora'   => 'unimed',
                'regiao'      => '',
                'plano'       => '',
                'acomodacao'  => $this->random(['enfermaria', 'apartamento'])
            ])
            ->post('/saude/simulacao');

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }
}
