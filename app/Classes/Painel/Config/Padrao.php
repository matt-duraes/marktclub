<?php

namespace App\Classes\Painel\Config;

use App\Classes\Painel\Config\Trait\PermissaoTrait;

final class Padrao
{
    use PermissaoTrait;

    public array $PERMISSAO = [];

    public function __construct()
    {
        $this->setarPropriedadePermissao();
    }

    /**
     * Pega a lista de scopes do usuário pelas permissões
     *
     * @param  array $equipe
     * @return array
     */
    public function scope(array $equipe): array
    {
        $permissoes = $this->montarPermissoes();
        $scopes = [];
        foreach ($equipe as $item) {
            if (!array_key_exists($item, $permissoes) || empty($permissoes[$item])) {
                continue;
            }
            $scopes = array_merge($scopes, $permissoes[$item]);
        }
        return array_values(arrayRemoverValorDuplicado($scopes));
    }

    /**
     * Monta a lista com as permissões do usuário
     *
     * @return array
     */
    public function montarPermissoes(): array
    {
        $retorno = [];
        foreach ($this->PERMISSAO as $painelApp) {
            foreach ($painelApp['permissao'] as $permissao => $apiScope) {
                $scope = $apiScope['scope'] ?? '';
                if (empty($scope)) {
                    $retorno[$permissao] = [];
                    continue;
                }
                $retorno[$permissao] = is_string($scope) ? [$scope] : $scope;
            }
        }
        return $retorno;
    }
}
