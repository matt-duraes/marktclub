<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class Decimal implements ModuleInterface
{

    use ValidarTrait;
    public function __toString()
    {
        return $this->decimal();
    }

    /**
     * Pega o valor padrão independente do tipo de modulo
     */
    public function valor()
    {
        return $this->decimal();
    }

    // doc
    /**
     * Modulo para decimal [0-9]{1,}.[0-9]{2}
     *
     * @param null|string $decimal Valor para o modulo
     */
    public function __construct(
        private ?string $decimal
    ) {
        if (empty($this->decimal)) {
            $this->vazio = true;
            $this->valido = false;
            $this->decimal = '';
            return;
        } else if (!$this->validarDecimal()) {
            $this->valido = false;
            $this->decimal = '';
            return;
        }
        $this->decimal = number_format($this->decimal, 2, '.', '');
    }

    // doc
    /**
     * Pega o valor decimal
     *
     * @return float|string Valor decinal
     */
    public function decimal(): float|string
    {
        return $this->decimal;
    }

    private function validarDecimal(): bool
    {
        return preg_match('/^[0-9]{0,}.[0-9]{1,2}$/', $this->decimal);
    }
}
