<?php

namespace App\Models\Site\Ativar;

use Helpers\ApiHelper;

final class GrupoModel
{
    public function buscarGrupos(): array
    {
        if (in_array('grupo', CAMPOS_PRIMEIRO_ACESSO)) {
            $dado = (new ApiHelper('usuario_grupo:listar'))
                ->json([
                    'empresa'   => sessao('CLUBE')->empresa,
                ])
                ->get('/usuario-grupo/select')
                ->array()['dado'] ?? [];
            return $dado;
        }

        return [];
    }
}
