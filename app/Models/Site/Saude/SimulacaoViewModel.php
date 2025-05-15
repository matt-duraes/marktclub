<?php

namespace App\Models\Site\Saude;

use App\Classes\Saude\Operadora;
use App\Classes\Saude\Operadoras\Amil\Amil;
use App\Classes\Saude\Operadoras\CNUFlorianopolis\CNUFlorianopolis;
use App\Classes\Saude\Operadoras\Unimed\Jundiai;
use App\Classes\Saude\Operadoras\Unimed\Natal;
use App\Classes\Saude\Operadoras\Unimed\Unimed;
use App\Classes\Saude\Operadoras\UnimedSeguro\UnimedSeguro;

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
            Operadora::UNIMED => (new Unimed())->pegarAcomodacoes(),
            Operadora::UNIMED_SEGURO => (new UnimedSeguro())->pegarAcomodacoes(),
            Operadora::CNU_FLORIANOPIS => (new CNUFlorianopolis())->pegarAcomodacoes(true),
            Operadora::UNIMED_JUNDIAI => (new Jundiai())->pegarAcomodacoes(true),
            Operadora::UNIMED_NATAL => (new Natal())->pegarAcomodacoes(),
            default => []
        };
    }

    /**
     * @return array
     */
    public function plano(): array
    {
        return match ($this->operadora) {
            Operadora::AMIL => (new Amil())->pegarPlanos(false),
            Operadora::CNU_FLORIANOPIS => (new CNUFlorianopolis())->pegarPlanos(false),
            Operadora::UNIMED_SEGURO => (new UnimedSeguro())->pegarPlanos(false),
            Operadora::UNIMED => (new Unimed())->pegarPlanos(false),
            Operadora::UNIMED_JUNDIAI => (new Jundiai())->pegarPlanos(false),
            Operadora::UNIMED_NATAL => (new Natal())->pegarPlanos(false),
            default => []
        };
    }

    /**
     * @return array
     */
    public function regiao(): array
    {
        return match ($this->operadora) {
            Operadora::AMIL => (new Amil())->pegarRegioes(),
            default => []
        };
    }

    /**
     * @return array
     */
    public function passoPasso(): array
    {
        $passoPasso = match ($this->operadora) {
            Operadora::AMIL => ['regiao', 'plano', 'simulacao', 'resultado'],
            Operadora::CNU_FLORIANOPIS => [
                'plano',
                'acomodacao',
                'simulacao',
                'resultado',
            ],
            Operadora::UNIMED, Operadora::UNIMED_SEGURO => ['acomodacao', 'simulacao', 'resultado'],
            Operadora::UNIMED_JUNDIAI, Operadora::UNIMED_NATAL => ['plano', 'simulacao', 'resultado'],
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
                'numero' => $i,
            ];
            $i++;
        }
        return $retorno;
    }
}
