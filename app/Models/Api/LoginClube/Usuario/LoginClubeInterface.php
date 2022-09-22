<?php

namespace App\Models\Api\LoginClube\Usuario;

use App\Models\Api\LoginClube\UsuarioEntity;

interface LoginClubeInterface
{
    public function fazerLogin(string $login, string $senha, int $idEmpresa): UsuarioEntity;
}
