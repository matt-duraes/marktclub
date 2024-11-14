<?php

if (!function_exists('tema')) {
    /**
     * Verifica qual tema vai ser usado
     *
     * @return retorna light para tema claro e dark para tema escuro
     */
    function tema(): string
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
