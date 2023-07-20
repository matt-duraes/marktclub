<?php

namespace App\Models\Api\ApiToken;

use Helpers\JwtHelper;
use App\Models\Api\ApiToken\Trait\ScopeTrait;
use App\Models\Api\ApiToken\Trait\TokenTrait;
use App\Models\Api\ApiToken\Trait\PegarAppTrait;
use App\Models\Api\ApiToken\Trait\PegarEmpresaTrait;

final class ValidarTokenCredentialModel
{
    use ScopeTrait;
    use TokenTrait;
    use PegarAppTrait;
    use PegarEmpresaTrait;

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

        $App = $this->pegarApp(['client_id', $clitenId]);
        if ($App->audience != $audience) {
            $this->erro403('ValidarTokenCredential - Audience do APP é invalido.');
        }

        $Empresa = $this->pegarEmpresa([
            ['id', $App->id_admin_empresa],
            ['status', 'in', [1, 2]]
        ]);

        if (vazio($Empresa)) {
            $this->erro403('ValidarTokenCredential - Não foi encontrado uma empresa.');
        }

        $Usuario = (object)[];
        $this->validarScope($scope, $App->scope_permitido);
        $this->criarDefinesDoToken($token, $App, $Empresa, $Usuario, $scope, $dado['gty']);

        return true;
    }

    private function erro403(string $mensagem = '', ?\Throwable $e = null)
    {
        mensagemStatus(403, localhost: $mensagem, error: $e);
    }
}
