<?php

namespace App\Models\Api\ApiToken\Trait;

use App\Classes\ApiToken\Tipo;
use App\Models\Api\ApiApp\AppEntity;
use App\Models\Api\ApiToken\PayloadModel;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\ApiToken\TokenAuthorizationEntity;

trait TokenTrait
{
    /**
     * Criar a define do token
     *
     * @param string                     $token   Token que o usuário usou
     * @param AppEntity                  $App     App do token
     * @param EquipeEntity|ClienteEntity $Usuario Usuário dependendo do tipo do token
     */
    public function criarDefinesDoToken(string $token, AppEntity $App, $Empresa, $Usuario, array $scope, string $tipo)
    {
        define('TOKEN', [
            'token'   => $token,
            'scope'   => $scope,
            'app'     => $App,
            'empresa' => $Empresa,
            'usuario' => $Usuario,
            'tipo'    => $tipo
        ]);
    }

    public function criarImplicitToken(
        AppEntity $App,
        EquipeEntity|ClienteEntity $Usuario,
        array $scope,
        string $audience,
        string $redirectUri,
        string $state,
        Tipo $tipo
    ) {
        $payload = (new PayloadModel($Usuario))->payload;

        $Token = new TokenAuthorizationEntity();
        return $Token->criarToken(
            $App,
            $payload,
            $scope,
            $audience,
            $redirectUri,
            $state,
            $tipo
        );
    }
}
