<?php

namespace Helpers;

use Michelf\Markdown;

final class MarkdownHelper
{
    public function __construct(private ?string $texto = null)
    {
        if (empty($texto)) {
            $this->texto = '';
            return;
        }
    }

    public function texto(string $texto = null)
    {
        $texto = !empty($texto) ? $texto : $this->texto;
        return Markdown::defaultTransform($texto);
    }
}
