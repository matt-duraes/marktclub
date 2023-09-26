<?php

namespace Helpers;

use Parsedown;

final class MarkdownHelper
{
    /**
     * Converter uma string Markdown em HTML
     *
     * @param string|null $texto Texto que deseja converter
     */
    public function __construct(
        private ?string $texto = null
    ) {
        if (empty($texto)) {
            $this->texto = '';
            return;
        }
    }

    public function texto(string $texto = null)
    {
        $texto = !empty($texto) ? $texto : $this->texto;
        $Parsedown = new Parsedown();
        return $Parsedown->text($texto);
    }
}
