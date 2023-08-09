<?php

namespace Tests\Api;

use App\Classes\Saude\Operadora;
use App\Classes\Saude\Operadoras\Amil\Planos as PlanosAmil;
use App\Classes\Saude\Operadoras\Amil\Regioes as RegioesAmil;
use App\Classes\Saude\Operadoras\CentralNacionalUnimedFlorianopolis\Planos as PlanosFloripa;
use Erro\Excecao;
use Tests\Api\Token\Clube;

final class SaudeSimulacaoTest extends Clube
{
    private string $idSimulacao;

    public function __construct()
    {
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

        $this->api('saude_simulacao:salvar');
        $simulacao = $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body([
                'titular'     => $this->dataPassada(),
                'dependentes' => jsonEncode($dependentes),
                'operadora'   => Operadora::AMIL,
                'regiao'      => RegioesAmil::SAO_PAULO,
                'plano'       => PlanosAmil::AMIL_S80QC,
                'acomodacao'  => ''
            ])
            ->post('/saude/simulacao')
            ->array()['dado'] ?? [];

        $this->idSimulacao = $simulacao['id'];
        $qtdDependentesSalvos = count($simulacao['dependentes']);

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
    public function realizarSimulacaoUnimedFloripaTest(): SaudeSimulacaoTest
    {
        $dependentes = $this->gerarDependentes();
        $qtdDependentes = count($dependentes);

        $this->api('saude_simulacao:salvar');
        $simulacao = $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body([
                'titular'     => $this->dataPassada(),
                'dependentes' => jsonEncode($dependentes),
                'operadora'   => Operadora::CENTRAL_NACIONAL_UNIMED_FLORIPA,
                'regiao'      => '',
                'plano'       => PlanosFloripa::REGIONAL,
                'acomodacao'  => 'enfermaria-30'
            ])
            ->post('/saude/simulacao')
            ->array()['dado'] ?? [];

        $this->idSimulacao = $simulacao['id'];
        $qtdDependentesSalvos = count($simulacao['dependentes']);

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
    public function realizarSimulacaoUnimedSeguroTest(): SaudeSimulacaoTest
    {
        $dependentes = $this->gerarDependentes();
        $qtdDependentes = count($dependentes);

        $this->api('saude_simulacao:salvar');
        $simulacao = $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body([
                'titular'     => $this->dataPassada(),
                'dependentes' => jsonEncode($dependentes),
                'operadora'   => Operadora::UNIMED_SEGURO,
                'regiao'      => '',
                'plano'       => '',
                'acomodacao'  => $this->random(['basico', 'versatil', 'pratico'])
            ])
            ->post('/saude/simulacao')
            ->array()['dado'] ?? [];

        $this->idSimulacao = $simulacao['id'];
        $qtdDependentesSalvos = count($simulacao['dependentes']);

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
    public function realizarSimulacaoUnimedTest(): SaudeSimulacaoTest
    {
        $dependentes = $this->gerarDependentes();
        $qtdDependentes = count($dependentes);

        $this->api('saude_simulacao:salvar');
        $simulacao = $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body([
                'titular'     => $this->dataPassada(),
                'dependentes' => jsonEncode($dependentes),
                'operadora'   => Operadora::UNIMED,
                'regiao'      => '',
                'plano'       => '',
                'acomodacao'  => $this->random(['enfermaria', 'apartamento'])
            ])
            ->post('/saude/simulacao')
            ->array()['dado'] ?? [];

        $this->idSimulacao = $simulacao['id'];
        $qtdDependentesSalvos = count($simulacao['dependentes']);

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
}
