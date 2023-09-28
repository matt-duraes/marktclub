<?php

namespace Tests\Api;

use App\Classes\Saude\Operadora;
use App\Classes\Saude\Operadoras\Amil\Planos as PlanosAmil;
use App\Classes\Saude\Operadoras\Amil\Regioes as RegioesAmil;
use App\Classes\Saude\Operadoras\CNUFlorianopolis\Planos as PlanosCNUFlorianopolis;
use Erro\Excecao;
use Tests\Token\Clube;

final class SaudeSimulacaoTest extends Clube
{
    private string $idSimulacao;

    public function __construct()
    {
        $this->pegarToken();
        parent::__construct();
    }

    /**
     * @return SaudeSimulacaoTest
     * @throws Excecao
     */
    public function realizarSimulacaoAmilTest(): SaudeSimulacaoTest
    {
        $dependentes = $this->gerarDependentes();
        $qtdDependentes = count($dependentes);

        $simulacao = $this
            ->Curl
            ->body([
                'titular'          => $this->dataPassada(),
                'lista_dependente' => jsonEncode($dependentes),
                'operadora'        => Operadora::AMIL,
                'regiao'           => RegioesAmil::SAO_PAULO,
                'plano'            => PlanosAmil::AMIL_S80QC,
                'acomodacao'       => ''
            ])
            ->post('/saude/simulacao')
            ->array();

        $this->idSimulacao = $simulacao['dado']['id'] ?? 'sem-id';
        $qtdDependentesSalvos = count($simulacao['dado']['lista_dependente'] ?? []);

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIgual(
                $qtdDependentes,
                $qtdDependentesSalvos,
                "Foram gerados $qtdDependentes dependentes, e $qtdDependentesSalvos foram simulados."
            )
            ->checkIndiceExiste('dado.id');
    }

    /**
     * @return array
     */
    private function gerarDependentes(): array
    {
        $dependentes = [];
        for ($i = 0; $i < $this->numero(1, 4); $i++) {
            $dependentes[] = $this->dataPassada();
        }
        return $dependentes;
    }

    /**
     * @return SaudeSimulacaoTest
     * @throws Excecao
     */
    public function buscarSimulacaoAmilTest(): SaudeSimulacaoTest
    {
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
    public function realizarSimulacaoCNUFlorianopolisTest(): SaudeSimulacaoTest
    {
        $dependentes = $this->gerarDependentes();
        $qtdDependentes = count($dependentes);

        $simulacao = $this
            ->Curl
            ->body([
                'titular'          => $this->dataPassada(),
                'lista_dependente' => jsonEncode($dependentes),
                'operadora'        => Operadora::CNU_FLORIANOPIS,
                'regiao'           => '',
                'plano'            => PlanosCNUFlorianopolis::REGIONAL,
                'acomodacao'       => 'enfermaria-30'
            ])
            ->post('/saude/simulacao')
            ->array();

        $this->idSimulacao = $simulacao['dado']['id'] ?? 'sem-id';
        $qtdDependentesSalvos = count($simulacao['dado']['lista_dependente'] ?? []);

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIgual(
                $qtdDependentes,
                $qtdDependentesSalvos,
                "Foram gerados $qtdDependentes dependentes, e $qtdDependentesSalvos foram simulados."
            )
            ->checkIndiceExiste('dado.id');
    }

    /**
     * @return SaudeSimulacaoTest
     * @throws Excecao
     */
    public function buscarSimulacaoFloripaTest(): SaudeSimulacaoTest
    {
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
    public function realizarSimulacaoUnimedSeguroTest(): SaudeSimulacaoTest
    {
        $dependentes = $this->gerarDependentes();
        $qtdDependentes = count($dependentes);

        $simulacao = $this
            ->Curl
            ->body([
                'titular'          => $this->dataPassada(),
                'lista_dependente' => jsonEncode($dependentes),
                'operadora'        => Operadora::UNIMED_SEGURO,
                'regiao'           => '',
                'plano'            => '',
                'acomodacao'       => $this->random(['basico', 'versatil', 'pratico'])
            ])
            ->post('/saude/simulacao')
            ->array();

        $this->idSimulacao = $simulacao['dado']['id'] ?? 'sem-id';
        $qtdDependentesSalvos = count($simulacao['dado']['lista_dependente'] ?? []);

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIgual(
                $qtdDependentes,
                $qtdDependentesSalvos,
                "Foram gerados $qtdDependentes dependentes, e $qtdDependentesSalvos foram simulados."
            )
            ->checkIndiceExiste('dado.id');
    }

    /**
     * @return SaudeSimulacaoTest
     * @throws Excecao
     */
    public function buscarSimulacaoUnimedSeguroTest(): SaudeSimulacaoTest
    {
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
    public function realizarSimulacaoUnimedTest(): SaudeSimulacaoTest
    {
        $dependentes = $this->gerarDependentes();
        $qtdDependentes = count($dependentes);

        $simulacao = $this
            ->Curl
            ->body([
                'titular'          => $this->dataPassada(),
                'lista_dependente' => jsonEncode($dependentes),
                'operadora'        => Operadora::UNIMED,
                'regiao'           => '',
                'plano'            => '',
                'acomodacao'       => $this->random(['enfermaria', 'apartamento'])
            ])
            ->post('/saude/simulacao')
            ->array();

        $this->idSimulacao = $simulacao['dado']['id'] ?? 'sem-id';
        $qtdDependentesSalvos = count($simulacao['dado']['lista_dependente'] ?? []);

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIgual(
                $qtdDependentes,
                $qtdDependentesSalvos,
                "Foram gerados $qtdDependentes dependentes, e $qtdDependentesSalvos foram simulados."
            )
            ->checkIndiceExiste('dado.id');
    }

    /**
     * @return SaudeSimulacaoTest
     * @throws Excecao
     */
    public function buscarSimulacaoUnimedTest(): SaudeSimulacaoTest
    {
        $this
            ->Curl
            ->get('/saude/simulacao/' . $this->idSimulacao);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('dado.id', $this->idSimulacao);
    }
}
