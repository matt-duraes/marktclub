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
        } elseif (is_array($texto)) {
            $br = array_key_exists('br', $texto) ? $texto['br'] : '';
            $en = array_key_exists('en', $texto) ? $texto['en'] : $br;
            $es = array_key_exists('es', $texto) ? $texto['es'] : $br;
        } else {
            $br = is_string($texto) ? $texto : '';
            $en = $br;
            $es = $br;
        }
        return <<<EOF
            <span class="lang_br">$br</span>
            <span class="lang_en">$en</span>
            <span class="lang_es">$es</span>
        EOF;
    }
}

if (!function_exists('strCssMargem')) {
    function strCssMargem(?int $topo, ?int $direita, ?int $baixo, ?int $esquerda, bool $style = true)
    {
        $margem = [];
        if (!empty($topo)) {
            $margem[] = 'margin-top:' . $topo . 'px';
        }
        if (!empty($direita)) {
            $margem[] = 'margin-right:' . $direita . 'px';
        }
        if (!empty($baixo)) {
            $margem[] = 'margin-bottom:' . $baixo . 'px';
        }
        if (!empty($esquerda)) {
            $margem[] = 'margin-left:' . $esquerda . 'px';
        }
        if (empty($margem)) {
            return '';
        }
        $margem = implode('; ', $margem);
        return $style ? 'style="' . $margem . '"' : $margem;
    }
}
