<?php

if (!function_exists('tema')) {
    function tema()
    {
        $tema = cookieExiste('TEMA') ? cookie('TEMA') : 'light';
        $tema = in_array($tema, ['light', 'automatico', 'dark', 'sistema']) ? $tema : 'light';

        $hora = date('H');
        if ($tema == 'automatico') {
            return $hora >= 8 && $hora <= 17 ? 'light' : 'dark';
        }
        return $tema;
    }
}
