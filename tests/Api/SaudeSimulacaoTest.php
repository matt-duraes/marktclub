<?php

namespace Tests\Api;

use Erro\Excecao;
use Tests\Api\Token\Clube;

final class SaudeSimulacaoTest extends Clube
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
        $dataNascimento = $this->dataPassada();

        $this->api('saude_simulacao:salvar');
        $this
            ->Curl
            ->body([
                'titular'     => $dataNascimento,
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
        $dependenteNumero = $this->numero(1, 4);
        for ($i = 0; $i < $dependenteNumero; $i++) {
            $dependentes[] = $this->dataPassada();
        }
        return $dependentes;
    }

    /**
     * @return SaudeSimulacaoTest
     * @throws Excecao
     */
    public function realizarSimulacaoUnimedFloripaTest(): SaudeSimulacaoTest
    {
        $titular = $this->dataPassada();

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
        $titular = $this->dataPassada();

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
        $titular = $this->dataPassada();

        $dependente = $this->gerarDependentes();
        $dado = $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body([
                'titular'     => $titular,
                'dependentes' => jsonEncode($dependente),
                'operadora'   => 'unimed',
                'regiao'      => '',
                'plano'       => '',
                'acomodacao'  => $this->random(['enfermaria', 'apartamento'])
            ])
            ->post('/saude/simulacao');

        $dependenteNumero = count($dependente);
        $dependenteCriado = count($dado->object()->dado->dependentes ?? []);

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIgual(
                $dependenteNumero,
                $dependenteCriado,
                'O número de dependente deveria ser ' . $dependenteNumero . ' mas foi ' . $dependenteCriado . '.'
            )
            ->checkIndiceExiste('dado.id');
    }
}
