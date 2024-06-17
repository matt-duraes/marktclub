<?php

namespace App\Models\Api\Parceiro\Externo\Trait;

use Helpers\OrmHelper;
use Where\Where as WhereWhere;

trait WhereTrait
{
    public function pegarWhere(): WhereWhere
    {
        $Where = new WhereWhere($this, $this->whereEquipe());
        $Where
            ->manual(['id_dono_empresa', TOKEN['empresa']->id])
            ->dataDeAte('data_criacao')
            ->linha(propriedade: 'categoria')
            ->linha(propriedade:'endereco_estado', condicao: 'json')
            ->linha(propriedade: 'status')
            ->seVazio(propriedade: 'pesquisa', vazio: false, callback: function () use ($Where) {
                $pesquisa = '%' . $this->pesquisa . '%';
                $Where->manual([
                    'OR',
                    ['titulo', 'like', $pesquisa],
                    ['subcategoria_tag', 'like', $pesquisa],
                    ['titulo_interno', 'like', $pesquisa]
                ]);
            });

        $idSubempresa = TOKEN['usuario']->id_admin_subempresa;
        if (!empty($idSubempresa)) {
            $Where->manual(['id_dono_subempresa', $idSubempresa]);
        }
        return $Where;
    }

    private function whereEquipe()
    {
        $idEquipe = TOKEN['usuario']->id;
        $permissao = TOKEN['usuario']->permissao;

        if (!$this->pExiste('equipe') || empty($this->equipe)) {
            return in_array('parceiro_externo_equipe', $permissao) ? [] : [['id_dono_equipe', $idEquipe]];
        }

        $idEquipe = (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarIdPeloUuid(
            $this->equipe,
            erroMensagem: 'Não foi encontrado o usuário pela busca.'
        );
        return [['id_dono_equipe', $idEquipe]];
    }
}
