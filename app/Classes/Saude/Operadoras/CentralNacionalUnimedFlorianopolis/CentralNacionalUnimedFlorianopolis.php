<?php

namespace App\Classes\Saude\Operadoras\CentralNacionalUnimedFlorianopolis;

use App\Classes\Saude\Operadoras\AbstractOperadora;
use App\Classes\Saude\Plano;
use Modules\Data;

class CentralNacionalUnimedFlorianopolis extends AbstractOperadora
{
    protected array $acomodacoes = [
        Plano::REGIONAL => [
            'enfermaria-50' => 6,
            'enfermaria-30' => 7
        ],
        Plano::ESTADUAL => [
            'enfermaria'  => 1,
            'apartamento' => 2
        ],
        Plano::NACIONAL => [
            'enfermaria'  => 1,
            'apartamento' => 2
        ]
    ];

    /**
     * @return int|null Código da Acomodação para o Banco de Dados, NULL caso não encontrado há acomodação
     */
    public function pegarCodigoAcomodacao(): ?int
    {
        if (!array_key_exists($this->plano->indice(), $this->acomodacoes)) {
            return null;
        } elseif (!array_key_exists($this->acomodacao, $this->acomodacoes[$this->plano->indice()])) {
            return null;
        }
        return $this->acomodacoes[$this->plano->indice()][$this->acomodacao];
    }

    /**
     * @param Data|null $dataNascimento Data de Nascimento (opcional)
     *
     * @return float|null Valor da simulação, NULL caso error ao simular
     */
    public function simularValor(Data $dataNascimento = null): ?float
    {
        if ($dataNascimento !== null) {
            $this->idade = $this->pegarIdade($dataNascimento) ?? 0;
        }

        if ($this->idade <= 18) {
            $this->valores = [272.11, 225.52, 273.45, 368.39, 321.03, 407.17];
        } elseif ($this->idade >= 19 && $this->idade <= 23) {
            $this->valores = [329.25, 272.89, 330.87, 445.75, 388.42, 492.68];
        } elseif ($this->idade >= 24 && $this->idade <= 28) {
            $this->valores = [395.11, 327.48, 397.05, 534.90, 466.11, 591.20];
        } elseif ($this->idade >= 29 && $this->idade <= 33) {
            $this->valores = [462.28, 383.15, 464.55, 625.83, 545.38, 691.70];
        } elseif ($this->idade >= 34 && $this->idade <= 38) {
            $this->valores = [545.48, 452.11, 548.17, 738.49, 643.53, 816.21];
        } elseif ($this->idade >= 39 && $this->idade <= 43) {
            $this->valores = [621.84, 515.39, 624.91, 841.88, 733.62, 930.49];
        } elseif ($this->idade >= 44 && $this->idade <= 48) {
            $this->valores = [702.69, 582.43, 706.15, 951.32, 828.98, 1051.46];
        } elseif ($this->idade >= 49 && $this->idade <= 53) {
            $this->valores = [871.32, 722.19, 875.62, 1179.62, 1027.94, 1303.78];
        } elseif ($this->idade >= 54 && $this->idade <= 58) {
            $this->valores = [1115.29, 924.40, 1120.81, 1509.92, 1315.77, 1668.84];
        } elseif ($this->idade >= 59) {
            $this->valores = [1594.86, 1321.90, 1602.74, 2159.20, 1881.57, 2386.45];
        }

        $listaTipoAcomodacao = [
            Plano::REGIONAL => ['enfermaria-30' => 0, 'enfermaria-50' => 1],
            Plano::ESTADUAL => ['enfermaria' => 2, 'apartamento' => 3],
            Plano::NACIONAL => ['enfermaria' => 4, 'apartamento' => 5]
        ];

        if (!array_key_exists($this->plano->indice(), $listaTipoAcomodacao)) {
            return null;
        } elseif (!array_key_exists($this->acomodacao, $listaTipoAcomodacao[$this->plano->indice()])) {
            return null;
        }
        return $this->valores[$listaTipoAcomodacao[$this->plano->indice()][$this->acomodacao]];
    }
}
