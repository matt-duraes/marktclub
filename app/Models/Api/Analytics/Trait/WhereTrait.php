<?php

namespace App\Models\Api\Analytics\Trait;

use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;

trait WhereTrait
{
    use ValidarEmpresaTrait;

    /**
     * @param bool $dataAcesso
     *
     * @return array
     * @throws Excecao
     */
    private function pegarWherePadrao(bool $dataAcesso = true): array
    {
        $where = [];
        $ormHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        if (!empty($this->empresa) && validarUuid($this->empresa, false)) {
            $idEmpresa = $ormHelper->pegarIdPeloUuid(
                $this->empresa,
                'Há empresa informada não foi encontrada',
                'Empresa inválida!'
            );
            $where[] = ['id_admin_empresa', $idEmpresa];
        } elseif (!empty($this->empresa) && is_array($this->empresa)) {
            $where[] = ['id_admin_empresa', 'in', $ormHelper->mudarListaUuidParaId($this->empresa)];
        } else {
            $where[] = ['id_admin_empresa', $this->idEmpresa];
        }

        if (!empty($this->subempresa) && validarUuid($this->subempresa, false)) {
            $idSubempresa = $ormHelper->pegarIdPeloUuid(
                $this->subempresa,
                'Há Subempresa informada não foi encontrada',
                'Subempresa inválida!'
            );
            $where[] = ['id_admin_subempresa', $idSubempresa];
        } elseif (!empty($this->subempresa) && is_array($this->subempresa)) {
            $where[] = ['id_admin_subempresa', 'in', $ormHelper->mudarListaUuidParaId($this->subempresa)];
        } elseif (empty($this->subempresa) && !empty($this->idSubempresa) && $this->idSubempresa != 0) {
            $where[] = ['id_admin_subempresa', $this->idSubempresa];
        }

        if ($dataAcesso) {
            $where[] = [
                'data_acesso', 'between', [
                    $this->dataInicial->banco() . ' 00:00:00', $this->dataFinal->banco() . ' 23:59:59'
                ]
            ];
        }
        return $where;
    }
}
