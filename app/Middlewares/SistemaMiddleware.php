<?php

namespace App\Middlewares;

final class SistemaMiddleware
{
    public function tipo(string $tipo)
    {
        $tipo = strCaixaAlta($tipo);
        if (SISTEMA == 'LOCALHOST') {
            return true;
        } elseif (SISTEMA != $tipo) {
            mensagemStatus(404);
        }
        return true;
    }
}
