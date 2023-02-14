<?php

namespace PainelApp\login\Models;

use PainelApp\login\Models\PainelModel;
use PainelApp\login\Models\LoginInterface;
use PainelApp\login\Models\BuscarUsuarioModel;
use PainelApp\login\Models\AutenticarUsuarioModel;

final class LoginAutorizadoModel
{
    public function __construct(
        LoginInterface $Login
    ) {
        new AutenticarUsuarioModel(
            Login: $Login
        );
        new BuscarUsuarioModel();
        new PainelModel();
    }
}
