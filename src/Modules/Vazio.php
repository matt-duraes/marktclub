<?php

namespace Modules;

final class Vazio implements ModuleInterface
{
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
}
