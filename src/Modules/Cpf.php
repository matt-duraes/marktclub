<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class Cpf implements ModuleInterface
{
    use ValidarTrait;

    public function __toString()
    {
        return $this->cpf();
    }

    /**
     * Pega o valor padrão independente do tipo de modulo
     */
    public function valor()
    {
        return $this->cpf();
    }

    // doc
    /**
     * Modulo para CPF
     *
     * @param null|string $cpf CPF para o modulo
     */
    public function __construct(
        private ?string $cpf
    ) {
        $this->colocarZero();
        if (empty($this->cpf)) {
            $this->vazio = true;
            $this->valido = false;
            $this->cpf = '';
            return;
        } elseif (!$this->validarCpf()) {
            $this->valido = false;
            $this->cpf = '';
            return;
        }

        $this->cpf = preg_replace('/[^0-9]/', '', $this->cpf);
    }

    private function colocarZero()
    {
        $cpf = !empty($this->cpf) ? preg_replace('/[^0-9]/', '', $this->cpf) : '';
        if (empty($cpf) || $cpf < 1 || mb_strlen($cpf) >= 11) {
            return;
        }
        $this->cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
    }

    // doc
    /**
     * Pega o valor do CPF com pontos
     *
     * @return string CPF com pontos
     */
    public function cpf(): string
    {
        $cpf = $this->cpf;
        return empty($cpf) ? ''
            : substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }

    // doc
    /**
     * Pega o valor do CPF sem os pontos
     *
     * @return int|string CPF sem os pontos
     */
    public function numero(): int|string
    {
        return (int)$this->cpf;
    }

    private function validarCpf(): bool
    {
        $cpf = preg_replace('/[^0-9]/', '', $this->cpf);
        if (strlen($cpf) != 11) {
            return false;
        }
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
}
