<?php

namespace App\Models\Api\ApiToken\Trait;

use stdClass;
use App\Classes\ApiToken\Tipo;
use App\Models\Api\ApiToken\PayloadModel;
use App\Models\Api\ApiToken\TokenAuthorizationEntity;

trait TokenTrait
{
    /**
     * Criar a define do token
     *
     * @param string   $token   Token que o usuário usou
     * @param stdClass $App     App que está em uso
     * @param stdClass $Empresa Empresa que está em uso
     * @param stdClass $Usuario Usuário que está em uso
     * @param array    $scope   Scope em uso
     * @param string   $tipo    Tipo de token
     */
    public function criarDefinesDoToken(
        string $token,
        stdClass $App,
        stdClass $Empresa,
        stdClass $Usuario,
        array $scope,
        string $tipo
    ) {
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
        stdClass $App,
        stdClass $Usuario,
        array $scope,
        string $audience,
        string $redirectUri,
        string $state,
        Tipo $tipo,
        ?string $chave = null
    ) {
        $payload = (new PayloadModel($Usuario, $App->audience, $chave))->payload;

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
