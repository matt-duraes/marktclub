<?php

namespace App\Models\Api\ApiToken;

use Helpers\JwtHelper;
use App\Models\Api\ApiApp\AppEntity;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;

final class ValidarTokenCredentialModel
{
    use Trait\ScopeTrait;
    use Trait\TokenTrait;

    public function validar(string $token): bool
    {
        $Jwt = new JwtHelper();
        if (!$Jwt->validar($token)) {
            $this->erro403('ValidarTokenCredential - Não foi possível validar JWT.');
        }
        $dado = $Jwt->decode($token);
        if (existeErro($dado, ['azp', 'aud', 'scope', 'gty'])) {
            $this->erro403('ValidarTokenCredential - O JWT não contem azp, aud, scope ou gty.');
        }

        $clitenId = $dado['azp'];
        $audience = $dado['aud'];
        $scope = explode(' ', $dado['scope']);

        try {
            $App = new AppEntity();
            $App->buscar(['client_id', $clitenId]);
        } catch (\Throwable $e) {
            $this->erro403('ValidarTokenCredential - Erro ao buscar o APP.', $e);
        }

        if ($App->audience != $audience) {
            $this->erro403('ValidarTokenCredential - Audience do APP é invalido.');
        }

        try {
            $Empresa = new EmpresaEntity();
            $Empresa->buscar([
                ['id', $App->id_admin_empresa],
                ['status', 'in', [1, 2]]
            ]);
        } catch (\Throwable $e) {
            $this->erro403('ValidarTokenCredential - Não foi encontrado uma empresa.', $e);
        }

        $Usuario = [];

        $this->validarScope($scope, $App->scope_permitido);
        $this->criarDefinesDoToken($token, $App, $Empresa, $Usuario, $scope, $dado['gty']);

        return true;
    }

    private function erro403(string $mensagem = '', ?\Throwable $e = null)
    {
        mensagemStatus(403, localhost: $mensagem, error: $e);
    }
}
