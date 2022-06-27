<?php

namespace App\Models\Api\LoginPainel;

use App\Models\Api\UsuarioEquipe\EquipeEntity;

final class LoginFacebookModel
{

    private EquipeEntity $Usuario;

    public function __construct(string $id)
    {
    }

    public function pegarUsuario(): EquipeEntity
    {
        return $this->Usuario;
    }
}
