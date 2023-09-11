<?php

namespace App\Helpers;

use Helpers\ApiHelper;

class ClubeApiHelper extends ApiHelper
{
    protected string $idUsuario;

    public function __construct(string $scope = null)
    {
        $token = is_null($scope) ? true : false;
        parent::__construct(scope: $scope, token: $token);
        $this->idUsuario = sessao('USUARIO.id');
    }
}
