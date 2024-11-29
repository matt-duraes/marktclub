<?php

if (!function_exists('arrayRemoverValorDuplicado')) {
    function arrayRemoverValorDuplicado(array $array): array
    {
        return array_unique($array);
    }
}
