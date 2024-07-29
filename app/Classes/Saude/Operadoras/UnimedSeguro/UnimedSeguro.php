<?php

namespace App\Classes\Saude\Operadoras\UnimedSeguro;

use Exception;
use Modules\Data;
use App\Classes\Saude\Operadoras\AbstractOperadora;

class UnimedSeguro extends AbstractOperadora
{
    private const ACOMODACAO_BASICO = 'basico';
    private const ACOMODACAO_PRATICO = 'pratico';
    private const ACOMODACAO_VERSATIL = 'versatil';

    protected array $acomodacoes = [
        self::ACOMODACAO_BASICO, self::ACOMODACAO_PRATICO, self::ACOMODACAO_VERSATIL
    ];
    protected array $acomodacaoCodigo = [
        self::ACOMODACAO_BASICO   => 3,
        self::ACOMODACAO_PRATICO  => 4,
        self::ACOMODACAO_VERSATIL => 5
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
            $this->idade = $this->pegarIdade($dataNascimento) ?? 0;
        }

        if ($this->idade <= 18) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 375.32,
                self::ACOMODACAO_PRATICO  => 498.71,
                self::ACOMODACAO_VERSATIL => 600.10
            ];
        } elseif ($this->idade >= 19 && $this->idade <= 23) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 452.51,
                self::ACOMODACAO_PRATICO  => 604.78,
                self::ACOMODACAO_VERSATIL => 715.11
            ];
        } elseif ($this->idade >= 24 && $this->idade <= 28) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 521.90,
                self::ACOMODACAO_PRATICO  => 700.06,
                self::ACOMODACAO_VERSATIL => 826.96
            ];
        } elseif ($this->idade >= 29 && $this->idade <= 33) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 594.55,
                self::ACOMODACAO_PRATICO  => 799.80,
                self::ACOMODACAO_VERSATIL => 950.57
            ];
        } elseif ($this->idade >= 34 && $this->idade <= 38) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 684.16,
                self::ACOMODACAO_PRATICO  => 919.89,
                self::ACOMODACAO_VERSATIL => 1093.81
            ];
        } elseif ($this->idade >= 39 && $this->idade <= 43) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 805.78,
                self::ACOMODACAO_PRATICO  => 1072.79,
                self::ACOMODACAO_VERSATIL => 1288.23
            ];
        } elseif ($this->idade >= 44 && $this->idade <= 48) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 939.38,
                self::ACOMODACAO_PRATICO  => 1243.24,
                self::ACOMODACAO_VERSATIL => 1501.85
            ];
        } elseif ($this->idade >= 49 && $this->idade <= 53) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 1259.36,
                self::ACOMODACAO_PRATICO  => 1678.14,
                self::ACOMODACAO_VERSATIL => 2032.37
            ];
        } elseif ($this->idade >= 54 && $this->idade <= 58) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 1700.03,
                self::ACOMODACAO_PRATICO  => 2264.78,
                self::ACOMODACAO_VERSATIL => 2748.05
            ];
        } elseif ($this->idade >= 59) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 2236.95,
                self::ACOMODACAO_PRATICO  => 2992.35,
                self::ACOMODACAO_VERSATIL => 3594.25
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
