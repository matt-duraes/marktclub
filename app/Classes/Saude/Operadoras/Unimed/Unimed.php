<?php

namespace App\Classes\Saude\Operadoras\Unimed;

use Exception;
use Modules\Data;
use App\Classes\Saude\Acomodacao;
use App\Classes\Saude\Operadoras\AbstractOperadora;

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
                Acomodacao::ACOMODACAO_ENFERMARIA  => 337.27,
                Acomodacao::ACOMODACAO_APARTAMENTO => 406.27
            ];
        } elseif ($this->idade >= 19 && $this->idade <= 23) {
            $this->valores = [
                Acomodacao::ACOMODACAO_ENFERMARIA  => 363.62,
                Acomodacao::ACOMODACAO_APARTAMENTO => 437.63
            ];
        } elseif ($this->idade >= 24 && $this->idade <= 28) {
            $this->valores = [
                Acomodacao::ACOMODACAO_ENFERMARIA  => 392.68,
                Acomodacao::ACOMODACAO_APARTAMENTO => 472.63
            ];
        } elseif ($this->idade >= 29 && $this->idade <= 33) {
            $this->valores = [
                Acomodacao::ACOMODACAO_ENFERMARIA  => 452.83,
                Acomodacao::ACOMODACAO_APARTAMENTO => 538.33
            ];
        } elseif ($this->idade >= 34 && $this->idade <= 38) {
            $this->valores = [
                Acomodacao::ACOMODACAO_ENFERMARIA  => 489.03,
                Acomodacao::ACOMODACAO_APARTAMENTO => 581.34
            ];
        } elseif ($this->idade >= 39 && $this->idade <= 43) {
            $this->valores = [
                Acomodacao::ACOMODACAO_ENFERMARIA  => 518.63,
                Acomodacao::ACOMODACAO_APARTAMENTO => 622.89
            ];
        } elseif ($this->idade >= 44 && $this->idade <= 48) {
            $this->valores = [
                Acomodacao::ACOMODACAO_ENFERMARIA  => 826.40,
                Acomodacao::ACOMODACAO_APARTAMENTO => 995.67
            ];
        } elseif ($this->idade >= 49 && $this->idade <= 53) {
            $this->valores = [
                Acomodacao::ACOMODACAO_ENFERMARIA  => 861.91,
                Acomodacao::ACOMODACAO_APARTAMENTO => 1038.42
            ];
        } elseif ($this->idade >= 54 && $this->idade <= 58) {
            $this->valores = [
                Acomodacao::ACOMODACAO_ENFERMARIA  => 897.34,
                Acomodacao::ACOMODACAO_APARTAMENTO => 1081.20
            ];
        } elseif ($this->idade >= 59) {
            $this->valores = [
                Acomodacao::ACOMODACAO_ENFERMARIA  => 1868.86,
                Acomodacao::ACOMODACAO_APARTAMENTO => 2139.75
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
