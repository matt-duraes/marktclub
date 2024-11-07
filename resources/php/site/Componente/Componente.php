<?php

namespace ResourcesSite\Componente;

abstract class Componente
{
    protected array $css = [];
    protected array $attr = [];

    protected function attr(array $attr)
    {
        if (!empty($this->attr)) {
            $attr = array_merge($attr, $this->attr);
        }
        if (array_key_exists('target', $attr) && $attr['target'] == '_blank' && !array_key_exists('rel', $attr)) {
            $attr['rel'] = 'nofollow noopener';
        }
        $attrFinal = [];
        foreach ($attr as $ind => $val) {
            $attrFinal[] = $ind . '="' . $val . '"';
        }
        $this->attr = $attrFinal;
        return $this;
    }

    public function css(array $css)
    {
        if (!empty($this->css)) {
            $css = array_merge($css, $this->css);
        }
        $this->css = $css;
        return $this;
    }
}
