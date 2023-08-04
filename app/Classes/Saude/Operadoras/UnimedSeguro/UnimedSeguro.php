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
                self::ACOMODACAO_BASICO   => 302.96, self::ACOMODACAO_PRATICO => 402.57,
                self::ACOMODACAO_VERSATIL => 484.41
            ];
        } elseif ($this->idade >= 19 && $this->idade <= 23) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 365.27, self::ACOMODACAO_PRATICO => 488.18,
                self::ACOMODACAO_VERSATIL => 577.25
            ];
        } elseif ($this->idade >= 24 && $this->idade <= 28) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 421.29, self::ACOMODACAO_PRATICO => 565.10,
                self::ACOMODACAO_VERSATIL => 667.54
            ];
        } elseif ($this->idade >= 29 && $this->idade <= 33) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 479.93, self::ACOMODACAO_PRATICO => 645.61,
                self::ACOMODACAO_VERSATIL => 767.32
            ];
        } elseif ($this->idade >= 34 && $this->idade <= 38) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 552.27, self::ACOMODACAO_PRATICO => 742.55,
                self::ACOMODACAO_VERSATIL => 882.94
            ];
        } elseif ($this->idade >= 39 && $this->idade <= 43) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 650.44, self::ACOMODACAO_PRATICO => 865.98,
                self::ACOMODACAO_VERSATIL => 1039.89
            ];
        } elseif ($this->idade >= 44 && $this->idade <= 48) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 758.29, self::ACOMODACAO_PRATICO => 1003.56,
                self::ACOMODACAO_VERSATIL => 1212.32
            ];
        } elseif ($this->idade >= 49 && $this->idade <= 53) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 1016.58, self::ACOMODACAO_PRATICO => 1354.63,
                self::ACOMODACAO_VERSATIL => 1640.57
            ];
        } elseif ($this->idade >= 54 && $this->idade <= 58) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 1372.30, self::ACOMODACAO_PRATICO => 1828.17,
                self::ACOMODACAO_VERSATIL => 2218.28
            ];
        } elseif ($this->idade >= 59) {
            $this->valores = [
                self::ACOMODACAO_BASICO   => 1805.72, self::ACOMODACAO_PRATICO => 2415.49,
                self::ACOMODACAO_VERSATIL => 2901.35
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
