<?php

namespace App\Models\Api\ApiToken\Trait;

use App\Models\Api\ApiApp\AppEntity;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
use App\Models\Api\UsuarioCliente\ClienteEntity;

trait TokenTrait
{
    /**
     * Criar a define do token
     *
     * @param string                        $token      Token que o usuário usou
     * @param AppEntity                     $App        App do token
     * @param EquipeEntity|ClienteEntity   $Usuario    Usuário dependendo do tipo do token
     */
    public function criarToken(string $token, AppEntity $App, $Empresa, $Usuario, array $scope, string $tipo)
    {
        define('TOKEN', [
            'token' => $token,
            'scope' => $scope,
            'app' => $App,
            'empresa' => $Empresa,
            'usuario' => $Usuario,
            'tipo' => $tipo
        ]);
    }
}
