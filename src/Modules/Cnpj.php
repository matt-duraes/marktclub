<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class Cnpj implements ModuleInterface
{
    use ValidarTrait;

    public function __toString()
    {
        return $this->cnpj();
    }

    /**
     * Pega o valor padrão independente do tipo de modulo
     */
    public function valor()
    {
        return $this->numero();
    }

    // doc
    /**
     * Valor que deve ser enviado para o banco de dados
     *
     * @return mixed
     */
    public function banco(): mixed
    {
        return $this->numero();
    }

    // doc
    /**
     * Modulo para CNPJ
     *
     * @param null|string $cnpj CNPJ para o modulo
     */
    public function __construct(
        private ?string $cnpj = null,
    ) {
        $this->colocarZero();
        if (empty($this->cnpj)) {
            $this->vazio = true;
            $this->valido = false;
            $this->cnpj = '';
            return;
        } elseif (!$this->validarCnpj()) {
            $this->valido = false;
            $this->cnpj = '';
            return;
        }
        $this->cnpj = preg_replace('/[^0-9]/', '', $this->cnpj);
    }

    private function colocarZero()
    {
        $cnpj = !empty($this->cnpj) ? preg_replace('/[^0-9]/', '', $this->cnpj) : '';
        if (empty($cnpj) || $cnpj < 1 || mb_strlen($cnpj) >= 14) {
            return;
        }
        $this->cnpj = str_pad($cnpj, 14, '0', STR_PAD_LEFT);
    }

    // doc
    /**
     * Pega o CNPJ em formato com pontos
     *
     * @return string CNPJ com os pontos
     */
    public function cnpj(): string
    {
        $cnpj = $this->cnpj;
        return empty($cnpj) ? ''
            : substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3)
            . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
    }

    // doc
    /**
     * Pega o CNPJ no formato de apenas números
     *
     * @return int|string CNPJ sem os pontos
     */
    public function numero(): int|string
    {
        return !empty($this->cnpj) ? (int)$this->cnpj : '';
    }

    private function validarCnpj(): bool
    {
        $cnpj = preg_replace('/[^0-9]/', '', $this->cnpj);
        if (strlen($cnpj) != 14) {
            return false;
        }
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }
}
