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
            $this->valores = [self::ACOMODACAO_ENFERMARIA => 235.92, self::ACOMODACAO_APARTAMENTO => 284.18];
        } elseif ($this->idade >= 19 && $this->idade <= 23) {
            $this->valores = [self::ACOMODACAO_ENFERMARIA => 254.35, self::ACOMODACAO_APARTAMENTO => 306.12];
        } elseif ($this->idade >= 24 && $this->idade <= 28) {
            $this->valores = [self::ACOMODACAO_ENFERMARIA => 274.69, self::ACOMODACAO_APARTAMENTO => 330.60];
        } elseif ($this->idade >= 29 && $this->idade <= 33) {
            $this->valores = [self::ACOMODACAO_ENFERMARIA => 316.75, self::ACOMODACAO_APARTAMENTO => 376.56];
        } elseif ($this->idade >= 34 && $this->idade <= 38) {
            $this->valores = [self::ACOMODACAO_ENFERMARIA => 342.09, self::ACOMODACAO_APARTAMENTO => 406.66];
        } elseif ($this->idade >= 39 && $this->idade <= 43) {
            $this->valores = [self::ACOMODACAO_ENFERMARIA => 362.78, self::ACOMODACAO_APARTAMENTO => 435.70];
        } elseif ($this->idade >= 44 && $this->idade <= 48) {
            $this->valores = [self::ACOMODACAO_ENFERMARIA => 578.07, self::ACOMODACAO_APARTAMENTO => 696.47];
        } elseif ($this->idade >= 49 && $this->idade <= 53) {
            $this->valores = [self::ACOMODACAO_ENFERMARIA => 602.91, self::ACOMODACAO_APARTAMENTO => 726.38];
        } elseif ($this->idade >= 54 && $this->idade <= 58) {
            $this->valores = [self::ACOMODACAO_ENFERMARIA => 627.69, self::ACOMODACAO_APARTAMENTO => 756.30];
        } elseif ($this->idade >= 59) {
            $this->valores = [self::ACOMODACAO_ENFERMARIA => 1307.28, self::ACOMODACAO_APARTAMENTO => 1496.77];
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
     * @return array Planos disponíveis na região
     */
    public function pegarPlanos(): array
    {
        return [];
    }
}
