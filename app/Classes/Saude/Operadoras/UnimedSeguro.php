<?php

namespace App\Classes\Saude\Operadoras;

use Modules\Data;

class UnimedSeguro extends AbstractOperadora
{
    protected array $acomodacoes = [
        'basico'   => 3,
        'pratico'  => 4,
        'versatil' => 5
    ];

    /**
     * @return int|null Código da Acomodação para o Banco de Dados, NULL caso não encontrado há acomodação
     */
    public function pegarCodigoAcomodacao(): ?int
    {
        if (!array_key_exists($this->acomodacao, $this->acomodacoes)) {
            return null;
        }
        return $this->acomodacoes[$this->acomodacao];
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
            $this->valores = ['basico' => 302.96, 'pratico' => 402.57, 'versatil' => 484.41];
        } elseif ($this->idade >= 19 && $this->idade <= 23) {
            $this->valores = ['basico' => 365.27, 'pratico' => 488.18, 'versatil' => 577.25];
        } elseif ($this->idade >= 24 && $this->idade <= 28) {
            $this->valores = ['basico' => 421.29, 'pratico' => 565.10, 'versatil' => 667.54];
        } elseif ($this->idade >= 29 && $this->idade <= 33) {
            $this->valores = ['basico' => 479.93, 'pratico' => 645.61, 'versatil' => 767.32];
        } elseif ($this->idade >= 34 && $this->idade <= 38) {
            $this->valores = ['basico' => 552.27, 'pratico' => 742.55, 'versatil' => 882.94];
        } elseif ($this->idade >= 39 && $this->idade <= 43) {
            $this->valores = ['basico' => 650.44, 'pratico' => 865.98, 'versatil' => 1039.89];
        } elseif ($this->idade >= 44 && $this->idade <= 48) {
            $this->valores = ['basico' => 758.29, 'pratico' => 1003.56, 'versatil' => 1212.32];
        } elseif ($this->idade >= 49 && $this->idade <= 53) {
            $this->valores = ['basico' => 1016.58, 'pratico' => 1354.63, 'versatil' => 1640.57];
        } elseif ($this->idade >= 54 && $this->idade <= 58) {
            $this->valores = ['basico' => 1372.30, 'pratico' => 1828.17, 'versatil' => 2218.28];
        } elseif ($this->idade >= 59) {
            $this->valores = ['basico' => 1805.72, 'pratico' => 2415.49, 'versatil' => 2901.35];
        }

        if (!array_key_exists($this->acomodacao, $this->valores)) {
            return null;
        }
        return $this->valores[$this->acomodacao];
    }
}
