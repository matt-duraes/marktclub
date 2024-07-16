<?php

namespace App\Classes\Saude\Operadoras\Unimed;

use App\Classes\Saude\Acomodacao;
use App\Classes\Saude\Operadoras\AbstractOperadora;
use Exception;
use Modules\Data;

class Unimed extends AbstractOperadora
{
    protected array $acomodacoes = [
        Acomodacao::ACOMODACAO_ENFERMARIA, Acomodacao::ACOMODACAO_APARTAMENTO
    ];
    protected array $acomodacaoCodigo = [
        Acomodacao::ACOMODACAO_ENFERMARIA  => 1,
        Acomodacao::ACOMODACAO_APARTAMENTO => 2
    ];

    /**
     * @return int|null Código da Acomodação para o Banco de Dados, NULL caso não encontrado há acomodação
     */
    public function pegarCodigoAcomodacao(): ?int
    {
        if (!in_array($this->acomodacaoSelecionada, $this->acomodacoes)) {
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
                Acomodacao::ACOMODACAO_ENFERMARIA  => 299.72,
                Acomodacao::ACOMODACAO_APARTAMENTO => 361.03
            ];
        } elseif ($this->idade >= 19 && $this->idade <= 23) {
            $this->valores = [
                Acomodacao::ACOMODACAO_ENFERMARIA  => 323.13,
                Acomodacao::ACOMODACAO_APARTAMENTO => 388.90
            ];
        } elseif ($this->idade >= 24 && $this->idade <= 28) {
            $this->valores = [
                Acomodacao::ACOMODACAO_ENFERMARIA  => 348.96,
                Acomodacao::ACOMODACAO_APARTAMENTO => 420.00
            ];
        } elseif ($this->idade >= 29 && $this->idade <= 33) {
            $this->valores = [
                Acomodacao::ACOMODACAO_ENFERMARIA  => 402.41,
                Acomodacao::ACOMODACAO_APARTAMENTO => 478.39
            ];
        } elseif ($this->idade >= 34 && $this->idade <= 38) {
            $this->valores = [
                Acomodacao::ACOMODACAO_ENFERMARIA  => 434.58,
                Acomodacao::ACOMODACAO_APARTAMENTO => 516.61
            ];
        } elseif ($this->idade >= 39 && $this->idade <= 43) {
            $this->valores = [
                Acomodacao::ACOMODACAO_ENFERMARIA  => 460.88,
                Acomodacao::ACOMODACAO_APARTAMENTO => 553.53
            ];
        } elseif ($this->idade >= 44 && $this->idade <= 48) {
            $this->valores = [
                Acomodacao::ACOMODACAO_ENFERMARIA  => 734.38,
                Acomodacao::ACOMODACAO_APARTAMENTO => 884.80
            ];
        } elseif ($this->idade >= 49 && $this->idade <= 53) {
            $this->valores = [
                Acomodacao::ACOMODACAO_ENFERMARIA  => 765.94,
                Acomodacao::ACOMODACAO_APARTAMENTO => 922.79
            ];
        } elseif ($this->idade >= 54 && $this->idade <= 58) {
            $this->valores = [
                Acomodacao::ACOMODACAO_ENFERMARIA  => 797.42,
                Acomodacao::ACOMODACAO_APARTAMENTO => 960.81
            ];
        } elseif ($this->idade >= 59) {
            $this->valores = [
                Acomodacao::ACOMODACAO_ENFERMARIA  => 1660.77,
                Acomodacao::ACOMODACAO_APARTAMENTO => 1901.49
            ];
        }

        if (!array_key_exists($this->acomodacaoSelecionada, $this->valores)) {
            return null;
        }
        return $this->valores[$this->acomodacaoSelecionada];
    }

    /**
     * @param bool $all Pegar todas as acomodações independente do plano selecionado
     *
     * @return array|string Acomodações disponíveis no plano
     */
    public function pegarAcomodacoes(bool $all = false): array|string
    {
        return $this->acomodacoes;
    }

    /**
     * @return array Regiões disponíveis
     */
    public function pegarRegioes(): array
    {
        return [];
    }

    /**
     * @param bool $semNomes *
     *
     * @return array Planos disponíveis na região
     */
    public function pegarPlanos(bool $semNomes = true): array
    {
        return [];
    }
}
