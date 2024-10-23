<?php

if (!function_exists('strLink')) {
    function strLink($valor)
    {
        $eLink = !empty($valor) && is_string($valor) && (
            str_starts_with($valor, 'http://') || str_starts_with($valor, 'https://')
        );
        if (!$eLink) {
            return $valor;
        }
        if (
            preg_match('/^http(s){0,1}\:\/\/(youhuul.com|marktclub.com|markt.club|\{\{LINK\}\})/i', $valor)
        ) {
            $valor = preg_replace(
                '/^http(s){0,1}\:\/\/(youhuul.com|marktclub.com|markt.club|\{\{LINK\}\})/i',
                LINK,
                $valor
            );
        }
        return $valor;
    }
}

if (!function_exists('strLang')) {
    function strLang($texto)
    {
        if (is_object($texto)) {
            $br = object_key_exists('br', $texto) ? $texto->br : '';
            $en = object_key_exists('en', $texto) ? $texto->en : $br;
            $es = object_key_exists('es', $texto) ? $texto->es : $br;
        } else {
            $br = $texto;
            $en = $texto;
            $es = $texto;
        }
        return <<<EOF
            <span class="lang_br">$br</span>
            <span class="lang_en">$en</span>
            <span class="lang_es">$es</span>
        EOF;
    }
}
