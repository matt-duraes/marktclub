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
    public function realizarSimulacaoTest(): SaudeSimulacaoTest
    {
        $dataNascimento = $this->dataPassada();

        $dependentes = [];
        $dependenteNumero = $this->numero(1, 4);
        for ($i = 0; $i < $dependenteNumero; $i++) {
            $dependentes[] = $this->dataPassada();
        }

        $dado = $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body([
                'data_nascimento' => $dataNascimento,
                'dependentes'     => implode(',', $dependentes),
                'operadora'       => 'unimed',
                'regiao'          => '',
                'plano'           => '',
                'acomodacao'      => 'enfermaria'
            ])
            ->post('/saude/simulacao');

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
