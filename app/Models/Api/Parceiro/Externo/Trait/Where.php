<?php

namespace App\Models\Api\Parceiro\Externo\Trait;

use Helpers\OrmHelper;
use Where\Where as WhereWhere;

trait Where
{
    public function pegarWhere(): WhereWhere
    {
        $Where = new WhereWhere($this, $this->whereEquipe());
        $Where->manual(['id_dono_empresa', TOKEN['empresa']->id]);
        return $Where;
    }

    private function whereEquipe()
    {
        $idEquipe = TOKEN['usuario']->id;
        $permissao = TOKEN['usuario']->permissao;

        if (!$this->pExiste('equipe') || empty($this->equipe)) {
            return in_array('parceiro_externo_equipe', $permissao) ? [] : ['id_dono_equipe', $idEquipe];
        }

        $idEquipe = (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarIdPeloUuid(
            $this->equipe,
            erroMensagem: 'Não foi encontrado o usuário pela busca.'
        );
        return ['id_dono_equipe', $idEquipe];
    }
}
