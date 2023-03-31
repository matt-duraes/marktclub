<?php

namespace App\Middlewares\Api;

final class MarktClubMiddleware
{
    public function validar()
    {
        if (!defined('TOKEN') || !array_key_exists('app', TOKEN) || TOKEN['app']->id_admin_empresa != 1) {
            mensagemStatus(403);
        }
        return true;
    }
}
