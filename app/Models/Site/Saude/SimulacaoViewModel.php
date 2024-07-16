<?php

namespace App\Models\Site\Saude;

use App\Classes\Saude\Operadora;
use App\Classes\Saude\Operadoras\Amil\Amil;
use App\Classes\Saude\Operadoras\Unimed\Unimed;
use App\Classes\Saude\Operadoras\UnimedSeguro\UnimedSeguro;
use App\Classes\Saude\Operadoras\CNUFlorianopolis\CNUFlorianopolis;

final class SimulacaoViewModel
{
    /**
     * @param string $operadora
     */
    public function __construct(
        private readonly string $operadora
    ) {
    }

    /**
     * @return array|string
     */
    public function acomodacao(): array|string
    {
        return match ($this->operadora) {
            Operadora::UNIMED          => (new Unimed())->pegarAcomodacoes(),
            Operadora::UNIMED_SEGURO   => (new UnimedSeguro())->pegarAcomodacoes(),
            Operadora::CNU_FLORIANOPIS => (new CNUFlorianopolis())->pegarAcomodacoes(true),
            default                    => []
        };
    }

    /**
     * @return array
     */
    public function plano(): array
    {
        return match ($this->operadora) {
            Operadora::AMIL            => (new Amil())->pegarPlanos(false),
            Operadora::CNU_FLORIANOPIS => (new CNUFlorianopolis())->pegarPlanos(false),
            Operadora::UNIMED_SEGURO   => (new UnimedSeguro())->pegarPlanos(false),
            Operadora::UNIMED          => (new Unimed())->pegarPlanos(false),
            default                    => []
        };
    }

    /**
     * @return array
     */
    public function regiao(): array
    {
        return match ($this->operadora) {
            Operadora::AMIL => (new Amil())->pegarRegioes(),
            default         => []
        };
    }

    /**
     * @return array
     */
    public function passoPasso(): array
    {
        $passoPasso = match ($this->operadora) {
            Operadora::AMIL            => ['regiao', 'plano', 'simulacao', 'resultado'],
            Operadora::CNU_FLORIANOPIS => ['plano', 'acomodacao', 'simulacao', 'resultado'],
            Operadora::UNIMED, Operadora::UNIMED_SEGURO => ['acomodacao', 'simulacao', 'resultado'],
            default => []
        };
        return $this->montarPassoPasso($passoPasso);
    }

    /**
     * @param $lista
     *
     * @return array
     */
    private function montarPassoPasso($lista): array
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
