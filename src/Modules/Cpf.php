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

    // doc
    /**
     * Modulo para CPF
     *
     * @param null|string $cpf CPF para o modulo
     */
    public function __construct(
        private ?string $cpf
    ) {
        if (empty($this->cpf)) {
            $this->vazio = true;
            $this->valido = false;
            $this->cpf = '';
            return;
        } else if (!$this->validarCpf()) {
            $this->valido = false;
            $this->cpf = '';
            return;
        }

        $this->cpf = preg_replace('/[^0-9]/', '', $this->cpf);
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
        return empty($cpf) ? '' : substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }

    // doc
    /**
     * Pega o valor do CPF sem os pontos
     *
     * @return string CPF sem os pontos
     */
    public function numero(): string
    {
        return $this->cpf;
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
