<?php

namespace App\Classes\Saude\Operadoras\Unimed;

use App\Classes\Saude\Operadoras\AbstractOperadora;
use Exception;
use Modules\Data;

class Unimed extends AbstractOperadora
{
    private const ACOMODACAO_ENFERMARIA = 'enfermaria';
    private const ACOMODACAO_APARTAMENTO = 'apartamento';

    protected array $acomodacoes = [
        self::ACOMODACAO_ENFERMARIA, self::ACOMODACAO_APARTAMENTO
    ];
    protected array $acomodacaoCodigo = [
        self::ACOMODACAO_ENFERMARIA  => 1,
        self::ACOMODACAO_APARTAMENTO => 2
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
                self::ACOMODACAO_ENFERMARIA  => 273.36,
                self::ACOMODACAO_APARTAMENTO => 329.28
            ];
        } elseif ($this->idade >= 19 && $this->idade <= 23) {
            $this->valores = [
                self::ACOMODACAO_ENFERMARIA  => 294.72,
                self::ACOMODACAO_APARTAMENTO => 354.70
            ];
        } elseif ($this->idade >= 24 && $this->idade <= 28) {
            $this->valores = [
                self::ACOMODACAO_ENFERMARIA  => 318.28,
                self::ACOMODACAO_APARTAMENTO => 383.07
            ];
        } elseif ($this->idade >= 29 && $this->idade <= 33) {
            $this->valores = [
                self::ACOMODACAO_ENFERMARIA  => 367.02,
                self::ACOMODACAO_APARTAMENTO => 436.32
            ];
        } elseif ($this->idade >= 34 && $this->idade <= 38) {
            $this->valores = [
                self::ACOMODACAO_ENFERMARIA  => 396.38,
                self::ACOMODACAO_APARTAMENTO => 471.20
            ];
        } elseif ($this->idade >= 39 && $this->idade <= 43) {
            $this->valores = [
                self::ACOMODACAO_ENFERMARIA  => 420.35,
                self::ACOMODACAO_APARTAMENTO => 504.85
            ];
        } elseif ($this->idade >= 44 && $this->idade <= 48) {
            $this->valores = [
                self::ACOMODACAO_ENFERMARIA  => 669.61,
                self::ACOMODACAO_APARTAMENTO => 807.00
            ];
        } elseif ($this->idade >= 49 && $this->idade <= 53) {
            $this->valores = [
                self::ACOMODACAO_ENFERMARIA  => 698.59,
                self::ACOMODACAO_APARTAMENTO => 841.66
            ];
        } elseif ($this->idade >= 54 && $this->idade <= 58) {
            $this->valores = [
                self::ACOMODACAO_ENFERMARIA  => 727.30,
                self::ACOMODACAO_APARTAMENTO => 876.32
            ];
        } elseif ($this->idade >= 59) {
            $this->valores = [
                self::ACOMODACAO_ENFERMARIA  => 1514.75,
                self::ACOMODACAO_APARTAMENTO => 1734.31
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
