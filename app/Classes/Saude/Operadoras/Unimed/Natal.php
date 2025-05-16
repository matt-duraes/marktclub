<?php

namespace App\Classes\Saude\Operadoras\Unimed;

use App\Classes\Saude\Acomodacao;
use App\Classes\Saude\Operadoras\AbstractOperadora;
use App\Classes\Saude\Operadoras\Amil\Regioes;
use App\Classes\Saude\Operadoras\Unimed\Planos\Natal as Planos;
use Exception;
use Modules\Data;

class Natal extends AbstractOperadora
{
    protected array $planos           = [
        Planos::ESSENCIAL_FLEX_1,
        Planos::ESSENCIAL_FLEX_2,
        Planos::GREEN_FLEX_1_AD_CE,
        Planos::GREEN_FLEX_2_AD_CE,
        Planos::GREEN_FLEX_1_AD_CA,
        Planos::GREEN_FLEX_2_AD_CA,
    ];
    protected array $acomodacoes      = [
        Planos::ESSENCIAL_FLEX_1   => [Acomodacao::ACOMODACAO_ENFERMARIA],
        Planos::ESSENCIAL_FLEX_2   => [Acomodacao::ACOMODACAO_ENFERMARIA],
        Planos::GREEN_FLEX_1_AD_CE => [Acomodacao::ACOMODACAO_ENFERMARIA],
        Planos::GREEN_FLEX_2_AD_CE => [Acomodacao::ACOMODACAO_ENFERMARIA],
        Planos::GREEN_FLEX_1_AD_CA => [Acomodacao::ACOMODACAO_APARTAMENTO],
        Planos::GREEN_FLEX_2_AD_CA => [Acomodacao::ACOMODACAO_APARTAMENTO],
    ];
    protected array $acomodacaoCodigo = [
        Acomodacao::ACOMODACAO_ENFERMARIA  => 1,
        Acomodacao::ACOMODACAO_APARTAMENTO => 2,
    ];

    /**
     * @return int|null Código da Acomodação para o Banco de Dados, NULL caso não encontrado há acomodação
     */
    public function pegarCodigoAcomodacao(): ?int
    {
        if (!in_array($this->planoSelecionado, $this->planos)) {
            return null;
        } elseif (!in_array($this->acomodacaoSelecionada, $this->acomodacoes[$this->planoSelecionado])) {
            return null;
        }
        return $this->acomodacaoCodigo[$this->acomodacaoSelecionada];
    }

    /**
     * @param Data|null $dataNascimento Data de Nascimento (opcional)
     *
     * @return float|null Valor da simulação, NULL caso error ao simular
     * @throws Exception
     */
    public function simularValor(Data $dataNascimento = null): ?float
    {
        if ($dataNascimento !== null) {
            $this->idade = $this->pegarIdade($dataNascimento);
        }

        if ($this->idade <= 18) {
            $this->valores = [
                PLANOS::ESSENCIAL_FLEX_1   => 223.35,
                PLANOS::ESSENCIAL_FLEX_2   => 194.23,
                PLANOS::GREEN_FLEX_1_AD_CE => 299.23,
                PLANOS::GREEN_FLEX_2_AD_CE => 260.2,
                PLANOS::GREEN_FLEX_1_AD_CA => 389,
                PLANOS::GREEN_FLEX_2_AD_CA => 338.28,
            ];
        } elseif ($this->idade >= 19 && $this->idade <= 23) {
            $this->valores = [
                PLANOS::ESSENCIAL_FLEX_1   => 250.16,
                PLANOS::ESSENCIAL_FLEX_2   => 217.52,
                PLANOS::GREEN_FLEX_1_AD_CE => 353.09,
                PLANOS::GREEN_FLEX_2_AD_CE => 307.04,
                PLANOS::GREEN_FLEX_1_AD_CA => 459.06,
                PLANOS::GREEN_FLEX_2_AD_CA => 399.17,
            ];
        } elseif ($this->idade >= 24 && $this->idade <= 28) {
            $this->valores = [
                PLANOS::ESSENCIAL_FLEX_1   => 280.17,
                PLANOS::ESSENCIAL_FLEX_2   => 243.63,
                PLANOS::GREEN_FLEX_1_AD_CE => 416.67,
                PLANOS::GREEN_FLEX_2_AD_CE => 362.33,
                PLANOS::GREEN_FLEX_1_AD_CA => 541.64,
                PLANOS::GREEN_FLEX_2_AD_CA => 471.01,
            ];
        } elseif ($this->idade >= 29 && $this->idade <= 33) {
            $this->valores = [
                PLANOS::ESSENCIAL_FLEX_1   => 322.19,
                PLANOS::ESSENCIAL_FLEX_2   => 280.17,
                PLANOS::GREEN_FLEX_1_AD_CE => 483.32,
                PLANOS::GREEN_FLEX_2_AD_CE => 420.28,
                PLANOS::GREEN_FLEX_1_AD_CA => 628.32,
                PLANOS::GREEN_FLEX_2_AD_CA => 546.37,
            ];
        } elseif ($this->idade >= 34 && $this->idade <= 38) {
            $this->valores = [
                PLANOS::ESSENCIAL_FLEX_1   => 370.53,
                PLANOS::ESSENCIAL_FLEX_2   => 322.22,
                PLANOS::GREEN_FLEX_1_AD_CE => 560.65,
                PLANOS::GREEN_FLEX_2_AD_CE => 487.53,
                PLANOS::GREEN_FLEX_1_AD_CA => 728.83,
                PLANOS::GREEN_FLEX_2_AD_CA => 633.79,
            ];
        } elseif ($this->idade >= 39 && $this->idade <= 43) {
            $this->valores = [
                PLANOS::ESSENCIAL_FLEX_1   => 444.64,
                PLANOS::ESSENCIAL_FLEX_2   => 386.65,
                PLANOS::GREEN_FLEX_1_AD_CE => 650.33,
                PLANOS::GREEN_FLEX_2_AD_CE => 565.53,
                PLANOS::GREEN_FLEX_1_AD_CA => 845.48,
                PLANOS::GREEN_FLEX_2_AD_CA => 735.23,
            ];
        } elseif ($this->idade >= 44 && $this->idade <= 48) {
            $this->valores = [
                PLANOS::ESSENCIAL_FLEX_1   => 560.25,
                PLANOS::ESSENCIAL_FLEX_2   => 487.19,
                PLANOS::GREEN_FLEX_1_AD_CE => 819.45,
                PLANOS::GREEN_FLEX_2_AD_CE => 712.57,
                PLANOS::GREEN_FLEX_1_AD_CA => 1065.28,
                PLANOS::GREEN_FLEX_2_AD_CA => 926.37,
            ];
        } elseif ($this->idade >= 49 && $this->idade <= 53) {
            $this->valores = [
                PLANOS::ESSENCIAL_FLEX_1   => 745.13,
                PLANOS::ESSENCIAL_FLEX_2   => 647.95,
                PLANOS::GREEN_FLEX_1_AD_CE => 1032.5,
                PLANOS::GREEN_FLEX_2_AD_CE => 897.88,
                PLANOS::GREEN_FLEX_1_AD_CA => 1342.24,
                PLANOS::GREEN_FLEX_2_AD_CA => 1167.2,
            ];
        } elseif ($this->idade >= 54 && $this->idade <= 58) {
            $this->valores = [
                PLANOS::ESSENCIAL_FLEX_1   => 998.48,
                PLANOS::ESSENCIAL_FLEX_2   => 868.26,
                PLANOS::GREEN_FLEX_1_AD_CE => 1342.25,
                PLANOS::GREEN_FLEX_2_AD_CE => 1167.19,
                PLANOS::GREEN_FLEX_1_AD_CA => 1744.94,
                PLANOS::GREEN_FLEX_2_AD_CA => 1517.42,
            ];
        } elseif ($this->idade >= 59) {
            $this->valores = [
                PLANOS::ESSENCIAL_FLEX_1   => 1337.95,
                PLANOS::ESSENCIAL_FLEX_2   => 1163.47,
                PLANOS::GREEN_FLEX_1_AD_CE => 1744.93,
                PLANOS::GREEN_FLEX_2_AD_CE => 1517.37,
                PLANOS::GREEN_FLEX_1_AD_CA => 2268.4,
                PLANOS::GREEN_FLEX_2_AD_CA => 1972.61,
            ];
        }

        if (!array_key_exists($this->planoSelecionado, $this->valores)) {
            return null;
        }
        return $this->valores[$this->planoSelecionado];
    }

    /**
     * @param bool $all Pegar todas as acomodações, independente do plano selecionado
     *
     * @return array|string Acomodações disponíveis no plano
     */
    public function pegarAcomodacoes(bool $all = false): array|string
    {
        if ($all) {
            return array_keys($this->acomodacaoCodigo);
        }
        return match ($this->planoSelecionado) {
            Planos::ESSENCIAL_FLEX_1, Planos::ESSENCIAL_FLEX_2, Planos::GREEN_FLEX_1_AD_CE, planos::GREEN_FLEX_2_AD_CE => Acomodacao::ACOMODACAO_ENFERMARIA,
            Planos::GREEN_FLEX_1_AD_CA, Planos::GREEN_FLEX_2_AD_CA => Acomodacao::ACOMODACAO_APARTAMENTO,
        };
    }

    /**
     * @return array Regiões disponíveis
     */
    public function pegarRegioes(): array
    {
        return (new Regioes())->select();
    }

    /**
     * @return array Planos disponíveis na região
     */
    public function pegarPlanos(bool $semNomes = true): array
    {
        if ($semNomes) {
            return $this->planos;
        }
        return (new Planos())->select();
    }
}
