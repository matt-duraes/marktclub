<?php

namespace App\Classes\Saude\Operadoras\Unimed;

use App\Classes\Saude\Operadoras\AbstractOperadora;
use Modules\Data;

class Unimed extends AbstractOperadora
{
    protected array $acomodacoes = [
        'enfermaria'  => 1,
        'apartamento' => 2
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
            $this->valores = ['enfermaria' => 235.92, 'apartamento' => 284.18];
        } elseif ($this->idade >= 19 && $this->idade <= 23) {
            $this->valores = ['enfermaria' => 254.35, 'apartamento' => 306.12];
        } elseif ($this->idade >= 24 && $this->idade <= 28) {
            $this->valores = ['enfermaria' => 274.69, 'apartamento' => 330.60];
        } elseif ($this->idade >= 29 && $this->idade <= 33) {
            $this->valores = ['enfermaria' => 316.75, 'apartamento' => 376.56];
        } elseif ($this->idade >= 34 && $this->idade <= 38) {
            $this->valores = ['enfermaria' => 342.09, 'apartamento' => 406.66];
        } elseif ($this->idade >= 39 && $this->idade <= 43) {
            $this->valores = ['enfermaria' => 362.78, 'apartamento' => 435.70];
        } elseif ($this->idade >= 44 && $this->idade <= 48) {
            $this->valores = ['enfermaria' => 578.07, 'apartamento' => 696.47];
        } elseif ($this->idade >= 49 && $this->idade <= 53) {
            $this->valores = ['enfermaria' => 602.91, 'apartamento' => 726.38];
        } elseif ($this->idade >= 54 && $this->idade <= 58) {
            $this->valores = ['enfermaria' => 627.69, 'apartamento' => 756.30];
        } elseif ($this->idade >= 59) {
            $this->valores = ['enfermaria' => 1307.28, 'apartamento' => 1496.77];
        }

        if (!array_key_exists($this->acomodacao, $this->valores)) {
            return null;
        }
        return $this->valores[$this->acomodacao];
    }
}
