<?php

namespace App\Models\Site\Saude;

use App\Classes\Saude\Operadora;
use App\Classes\Saude\Operadoras\CentralNacionalUnimed\CentralNacionalUnimed;
use App\Classes\Saude\Operadoras\CentralNacionalUnimedFlorianopolis\CentralNacionalUnimedFlorianopolis;
use App\Classes\Saude\Operadoras\Amil\Amil;
use App\Classes\Saude\Operadoras\Unimed\Unimed;

final class SimulacaoViewModel
{
    public function __construct(
        private readonly string $operadora
    ) {
    }

    public function opcao()
    {
        $passos = match ($this->operadora) {
            Operadora::AMIL                            => ['Região', 'Plano', 'Simulação', 'Resultado'],
            Operadora::CENTRAL_NACIONAL_UNIMED_FLORIPA => ['Plano', 'Acomodação', 'Simulação', 'Resultado'],
            Operadora::CENTRAL_NACIONAL_UNIMED         => ['Plano', 'Acomodação', 'Simulação', 'Resultado'],
            Operadora::UNIMED                          => ['Acomodação', 'Simulação', 'Resultado'],
            default                                    => []
        };
        return $this->montarOpcao($passos);
    }

    private function montarOpcao($lista)
    {
        $i = 1;
        $retorno = [];
        foreach ($lista as $titulo) {
            $retorno[] = (object)[
                'numero' => $i,
                'titulo' => $titulo
            ];
            $i++;
        }
        return $retorno;
    }

    public function acomodacao()
    {
        return match ($this->operadora) {
            Operadora::UNIMED => (new Unimed())->pegarAcomodacoes(),
            default           => []
        };
    }

    public function plano()
    {
        return match ($this->operadora) {
            Operadora::AMIL                            => (new Amil())->pegarPlanos(false),
            Operadora::CENTRAL_NACIONAL_UNIMED_FLORIPA => (new CentralNacionalUnimedFlorianopolis())->pegarPlanos(false),
            Operadora::CENTRAL_NACIONAL_UNIMED         => (new CentralNacionalUnimed())->pegarPlanos(false),
            Operadora::UNIMED                          => (new Unimed())->pegarPlanos(false),
            default                                    => []
        };
    }

    public function regiao()
    {
        return match ($this->operadora) {
            Operadora::AMIL                            => (new Amil())->pegarRegioes(),
            Operadora::CENTRAL_NACIONAL_UNIMED_FLORIPA => (new CentralNacionalUnimedFlorianopolis())->pegarRegioes(),
            Operadora::CENTRAL_NACIONAL_UNIMED         => (new CentralNacionalUnimed())->pegarRegioes(),
            default                                    => []
        };
    }

    public function passoPasso()
    {
        $passoPasso = match ($this->operadora) {
            Operadora::AMIL                            => ['regiao', 'plano', 'simulacao', 'resultado'],
            Operadora::CENTRAL_NACIONAL_UNIMED_FLORIPA => ['plano', 'acomodacao', 'simulacao', 'resultado'],
            Operadora::CENTRAL_NACIONAL_UNIMED         => ['regiao', 'plano', 'simulacao', 'resultado'],
            Operadora::UNIMED                          => ['acomodacao', 'simulacao', 'resultado'],
            default                                    => []
        };
        return $this->montarPassoPasso($passoPasso);
    }

    private function montarPassoPasso($lista)
    {
        $retorno = [];
        $i = 1;
        foreach ($lista as $tipo) {
            $retorno[] = (object)[
                'tipo'   => $tipo,
                'numero' => $i
            ];
            $i++;
        }
        return $retorno;
    }
}
