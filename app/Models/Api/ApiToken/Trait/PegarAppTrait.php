<?php

namespace App\Models\Api\ApiToken\Trait;

use Helpers\OrmHelper;

trait PegarAppTrait
{
    private function pegarApp($where)
    {
        $App = new OrmHelper(TABELA_AUTH_APP);
        $App = $App->pegarPrimeiroRegistro(
            where: $where,
            campo: [
                'id', 'uuid', 'audience', 'id_admin_empresa', 'tempo_vida', 'scope_permitido', 'chave_publica', 'chave_privada',
                'redirect_uri', 'client_id'
            ],
            retorno: 'object'
        );
        $App->scope_permitido = jsonDecode($App->scope_permitido, true, true);
        $App->redirect_uri = jsonDecode($App->redirect_uri, true, true);
        return $App;
    }
}
