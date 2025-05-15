<?php

namespace App\Classes\Saude\Operadoras\Unimed;

use App\Classes\Saude\Acomodacao;
use App\Classes\Saude\Operadoras\AbstractOperadora;
use App\Classes\Saude\Operadoras\Amil\Regioes;
use App\Classes\Saude\Operadoras\Unimed\Planos\Jundiai as Planos;
use Exception;
use Modules\Data;

class Jundiai extends AbstractOperadora
{
    protected array $planos           = [
        Planos::FLEX_IDEAL,
        Planos::FLEX_PLUS,
        Planos::CLASSICO_IDEAL,
        Planos::CLASSICO_PLUS,
    ];
    protected array $acomodacoes      = [
        Planos::FLEX_IDEAL     => [
            Acomodacao::ACOMODACAO_ENFERMARIA,
        ],
        Planos::FLEX_PLUS      => [
            Acomodacao::ACOMODACAO_APARTAMENTO,
        ],
        Planos::CLASSICO_IDEAL => [
            Acomodacao::ACOMODACAO_ENFERMARIA,
        ],
        Planos::CLASSICO_PLUS  => [
            Acomodacao::ACOMODACAO_APARTAMENTO,
        ],
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
                Planos::FLEX_IDEAL     => 176.77,
                Planos::FLEX_PLUS      => 226.28,
                Planos::CLASSICO_IDEAL => 246.84,
                Planos::CLASSICO_PLUS  => 321.03,
            ];
        } elseif ($this->idade >= 19 && $this->idade <= 23) {
            $this->valores = [
                Planos::FLEX_IDEAL     => 203.27,
                Planos::FLEX_PLUS      => 260.18,
                Planos::CLASSICO_IDEAL => 283.85,
                Planos::CLASSICO_PLUS  => 363.33,
            ];
        } elseif ($this->idade >= 24 && $this->idade <= 28) {
            $this->valores = [
                Planos::FLEX_IDEAL     => 233.31,
                Planos::FLEX_PLUS      => 298.62,
                Planos::CLASSICO_IDEAL => 325.8,
                Planos::CLASSICO_PLUS  => 417.02,
            ];
        } elseif ($this->idade >= 29 && $this->idade <= 33) {
            $this->valores = [
                Planos::FLEX_IDEAL     => 268.66,
                Planos::FLEX_PLUS      => 343.87,
                Planos::CLASSICO_IDEAL => 375.16,
                Planos::CLASSICO_PLUS  => 480.22,
            ];
        } elseif ($this->idade >= 34 && $this->idade <= 38) {
            $this->valores = [
                Planos::FLEX_IDEAL     => 309.33,
                Planos::FLEX_PLUS      => 395.96,
                Planos::CLASSICO_IDEAL => 431.96,
                Planos::CLASSICO_PLUS  => 552.9,
            ];
        } elseif ($this->idade >= 39 && $this->idade <= 43) {
            $this->valores = [
                Planos::FLEX_IDEAL     => 358.83,
                Planos::FLEX_PLUS      => 459.31,
                Planos::CLASSICO_IDEAL => 501.08,
                Planos::CLASSICO_PLUS  => 641.38,
            ];
        } elseif ($this->idade >= 44 && $this->idade <= 48) {
            $this->valores = [
                Planos::FLEX_IDEAL     => 433.08,
                Planos::FLEX_PLUS      => 554.34,
                Planos::CLASSICO_IDEAL => 604.75,
                Planos::CLASSICO_PLUS  => 774.07,
            ];
        } elseif ($this->idade >= 49 && $this->idade <= 53) {
            $this->valores = [
                Planos::FLEX_IDEAL     => 563.91,
                Planos::FLEX_PLUS      => 721.79,
                Planos::CLASSICO_IDEAL => 787.45,
                Planos::CLASSICO_PLUS  => 1007.93,
            ];
        } elseif ($this->idade >= 54 && $this->idade <= 58) {
            $this->valores = [
                Planos::FLEX_IDEAL     => 761.9,
                Planos::FLEX_PLUS      => 975.23,
                Planos::CLASSICO_IDEAL => 1063.91,
                Planos::CLASSICO_PLUS  => 1361.8,
            ];
        } elseif ($this->idade >= 59) {
            $this->valores = [
                Planos::FLEX_IDEAL     => 1058.88,
                Planos::FLEX_PLUS      => 1355.36,
                Planos::CLASSICO_IDEAL => 1478.62,
                Planos::CLASSICO_PLUS  => 1892.63,
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
            Planos::CLASSICO_IDEAL, Planos::FLEX_IDEAL => Acomodacao::ACOMODACAO_ENFERMARIA,
            Planos::CLASSICO_PLUS, Planos::FLEX_PLUS => Acomodacao::ACOMODACAO_APARTAMENTO,
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
