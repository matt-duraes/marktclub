<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class Vazio implements ModuleInterface
{
    use ValidarTrait;

    public function __toString()
    {
        return '';
    }

    /**
     * Pega o valor padrão independente do tipo de modulo
     */
    public function valor()
    {
        return '';
    }

    // doc
    /**
     * Valor que deve ser enviado para o banco de dados
     *
     * @return mixed
     */
    public function banco(): mixed
    {
        return '';
    }
}
