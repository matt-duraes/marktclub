<?php

namespace Modules\Trait;

trait ValorRealTrait
{
    private mixed $valor_real = '';

    // doc
    /**
     * Retorna o valor real setado na classe
     *
     * @return mixed
     */
    public function real(): mixed
    {
        return $this->valor_real;
    }
}
