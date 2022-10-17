<?php

namespace App\Models\Api\ApiToken\Trait;

trait ScopeTrait
{
    private function pegarScope(array $scope, array $permitido): array
    {
        if (empty($scope)) {
            return $permitido;
        }
        $this->validarScope($scope, $permitido);
        return $scope;
    }

    private function validarScope(array $scope, array $permitido): void
    {
        foreach ($scope as $item) {
            if (!in_array($item, $permitido)) {
                mensagemErro('Sem permissão!', 'Você não tem permissão para acessar um ou mais scopes.', 403);
            }
        }
        return;
    }
}
