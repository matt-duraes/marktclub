<?php

namespace PainelApp\login\Models;

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
