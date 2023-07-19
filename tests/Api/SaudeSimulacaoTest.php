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
        $this
            ->api('saude_simulacao:buscar')
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
    public function realizarSimulacaoTest(): SaudeSimulacaoTest
    {
        $dataNascimento = (new Data($this->dataPassada()))->data();

        $dependentes = [];
        for ($i = 0; $i < 4; $i++) {
            $dependentes[] = (new Data($this->dataPassada()))->data();
        }

        $this
            ->api('saude_simulacao:salvar')
            ->Curl
            ->body([
                'data_nascimento' => $dataNascimento,
                'dependentes'     => implode(',', $dependentes),
                'operadora'       => 'unimed',
                'regiao'          => '',
                'plano'           => '',
                'acomodacao'      => 'enfermaria'
            ])
            ->post('/saude/simulacao');

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }
}
