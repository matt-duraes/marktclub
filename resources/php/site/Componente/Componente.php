<?php

namespace ResourcesSite\Componente;

abstract class Componente
{
    protected function attr(array $attr): string
    {
        if (array_key_exists('target', $attr) && $attr['target'] == '_blank' && !array_key_exists('rel', $attr)) {
            $attr['rel'] = 'nofollow noopener';
        }
        $attrFinal = [];
        foreach ($attr as $ind => $val) {
            $attrFinal[] = $ind . '="' . $val . '"';
        }
        return implode(' ', $attrFinal);
    }
}
