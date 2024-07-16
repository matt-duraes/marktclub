<?php

namespace ResourcesSite\Componente;

abstract class Componente
{
    protected function attr(array $attr): string
    {
        $attrFinal = [];
        foreach ($attr as $ind => $val) {
            $attrFinal[] = $ind . '="' . $val . '"';
        }
        return implode(' ', $attrFinal);
    }
}
