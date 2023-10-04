<?php

namespace App\Classes\Saude\Operadoras\UnimedSeguro;

use App\Classes\Saude\Operadoras\AbstractOperadora;
use Exception;
use Modules\Data;

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
                self::ACOMODACAO_BASICO   => 332.14,
                self::ACOMODACAO_PRATICO  => 441.34,
                self::ACOMODACAO_VERSATIL => 531.06
            ];
        } elseif ($this->idade >= 19 && $this->idade <= 23) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 400.45,
                self::ACOMODACAO_PRATICO  => 535.20,
                self::ACOMODACAO_VERSATIL => 682.84
            ];
        } elseif ($this->idade >= 24 && $this->idade <= 28) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 461.86,
                self::ACOMODACAO_PRATICO  => 619.52,
                self::ACOMODACAO_VERSATIL => 731.82
            ];
        } elseif ($this->idade >= 29 && $this->idade <= 33) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 526.15,
                self::ACOMODACAO_PRATICO  => 707.79,
                self::ACOMODACAO_VERSATIL => 841.21
            ];
        } elseif ($this->idade >= 34 && $this->idade <= 38) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 605.45,
                self::ACOMODACAO_PRATICO  => 814.06,
                self::ACOMODACAO_VERSATIL => 967.97
            ];
        } elseif ($this->idade >= 39 && $this->idade <= 43) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 713.08,
                self::ACOMODACAO_PRATICO  => 949.37,
                self::ACOMODACAO_VERSATIL => 1140.43
            ];
        } elseif ($this->idade >= 44 && $this->idade <= 48) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 831.31,
                self::ACOMODACAO_PRATICO  => 1100.21,
                self::ACOMODACAO_VERSATIL => 1329.07
            ];
        } elseif ($this->idade >= 49 && $this->idade <= 53) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 1114.48,
                self::ACOMODACAO_PRATICO  => 1485.08,
                self::ACOMODACAO_VERSATIL => 1798.56
            ];
        } elseif ($this->idade >= 54 && $this->idade <= 58) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 1504.45,
                self::ACOMODACAO_PRATICO  => 2004.23,
                self::ACOMODACAO_VERSATIL => 2431.90
            ];
        } elseif ($this->idade >= 59) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 1979.60,
                self::ACOMODACAO_PRATICO  => 2648.10,
                self::ACOMODACAO_VERSATIL => 3180.75
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
