<?php

namespace App\Models\Api\ApiApp;

use stdClass;
use ORM\Entity;

final class AppModel extends Entity
{
    protected string $_tabela = TABELA_AUTH_APP;

    public function listarAppPeloId(array $id): stdClass
    {
        $lista = $this->campo([
            'uuid', 'nome', 'descricao', 'chave_publica', 'client_id', 'secret_id', 'audience', 'chave_publica_fake',
            'client_id_fake', 'secret_id_fake', 'authorization_code', 'client_credentials', 'refresh_token',
            'redirect_uri', 'scope_permitido', 'tempo_vida'
        ])->where([
            ['id', 'in', $id],
            ['status', 1]
        ])->read();

        return $this->montarApp($lista);
    }

    private function montarApp(array $dado): stdClass
    {
        $retorno = [];
        $scope = [];
        foreach ($dado as $r) {
            $appScope = jsonDecode($r->scope_permitido, true, true);
            $scope = array_merge($scope, $appScope);
            $retorno[] = (object)[
                'id' => $r->uuid,
                'nome' => $r->nome,
                'descricao' => $r->descricao,
                'chave_publica' => $r->chave_publica,
                'client_id' => $r->client_id,
                'secret_id' => $r->secret_id,
                'audience' => $r->audience,
                'chave_publica_fake' => $r->chave_publica_fake,
                'client_id_fake' => $r->client_id_fake,
                'secret_id_fake' => $r->secret_id_fake,
                'redirect_uri' => $r->redirect_uri,
                'authorization_code' => $r->authorization_code == 1 ? 'sim' : 'nao',
                'client_credentials' => $r->client_credentials == 1 ? 'sim' : 'nao',
                'refresh_token' => $r->refresh_token == 1 ? 'sim' : 'nao',
                'scope' => $appScope,
                'vida' => $r->tempo_vida
            ];
        }

        return object([
            'lista' => $retorno,
            'scope' => array_keys(array_flip($scope))
        ]);
    }
}
