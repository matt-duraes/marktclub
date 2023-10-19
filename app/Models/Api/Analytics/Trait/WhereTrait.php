<?php

namespace App\Models\Api\Analytics\Trait;

use Helpers\OrmHelper;

trait WhereTrait
{
    private function pegarWherePadrao(bool $dataAcesso = true)
    {
        if ($dataAcesso) {
            $this->validarData($this->de, $this->ate);
            $where[] = ['data_acesso', 'between', [$this->de->banco() . ' 00:00:00', $this->ate->banco() . ' 23:59:59']];
        }

        if (empty($this->Empresa)) {
            $where[] = ['id_admin_empresa', TOKEN['empresa']->id];
            return $where;
        }

        $ormHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);

        if (!is_array($this->Empresa)) {
            $empresaId = $ormHelper->pegarIdPeloUuid($this->Empresa);
            $where[] = ['id_admin_empresa', $empresaId];
            return $where;
        }

        $empresaId = [];
        foreach ($this->Empresa as $e) {
            $empresaId[] = $ormHelper->pegarIdPeloUuid($e);
        }
        $where[] = ['id_admin_empresa', 'in', $empresaId];
        return $where;
    }

    private function validarData($de, $ate, int $diaMaximo = 366)
    {
        $diasDiferenca = dataDiferencaDia($de, $ate);
        if (empty($de)) {
            mensagemErro('Data obrigatória!', 'A data de começo da busca é obrigatória.');
        } elseif (!validarDate($de)) {
            mensagemErro('Data inválida!', 'A data de começo da busca não está em um formato válido.');
        } elseif (empty($ate)) {
            mensagemErro('Data obrigatória!', 'A data final da busca é obrigatória.');
        } elseif (!validarDate($ate)) {
            mensagemErro('Data inválida!', 'A data final da busca não está em um formato válido.');
        } elseif ($diasDiferenca > $diaMaximo) {
            mensagemErro(
                'Datas inválidas!',
                'Você deve fazer uma busca com no máximo ' . $diaMaximo . ' dia(s) de diferênça.'
            );
        } elseif ($ate < $de) {
            mensagemErro('Datas inválidas!', 'A data fianl da busca deve ser maior ou igual a data de começo.');
        }
    }
}
