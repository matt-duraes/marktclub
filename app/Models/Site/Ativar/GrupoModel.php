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

    public function buscarSlug()
    {
        if (in_array('trabalho_cargo', CAMPOS_PRIMEIRO_ACESSO)) {
            $dados = (new ApiHelper('comercial_empresa:buscar'))
                ->get('/empresa-slug/' .  sessao('CLUBE')->empresa)
                ->object();
            if($dados->dado->id != "") {
                sessao('EMPRESA.slug', $dados->dado->slug);
            }
        }
    }
}
