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
