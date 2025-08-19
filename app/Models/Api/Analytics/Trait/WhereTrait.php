<?php

namespace App\Models\Api\Analytics\Trait;

use Erro\Excecao;
use Helpers\OrmHelper;
use App\Models\Api\Trait\ValidarEmpresaTrait;

trait WhereTrait
{
    use ValidarEmpresaTrait;

    /**
     * @param bool   $dataAcesso
     * @param string $colunaTabela
     * @param bool   $subempresa
     *
     * @return array
     * @throws Excecao
     */
    private function pegarWherePadrao(
        bool $dataAcesso = true,
        string $colunaTabela = 'data_acesso',
        bool $subempresa = true
    ): array {
        $where = [];
        $ormHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        if ($this->idEmpresa === 1 && !empty($this->empresa) && validarUuid($this->empresa, false)) {
            $idEmpresa = $ormHelper->pegarIdPeloUuid(
                $this->empresa,
                'Há empresa informada não foi encontrada',
                'Empresa inválida!'
            );
            $where[] = ['id_admin_empresa', $idEmpresa];
        } elseif ($this->idEmpresa === 1 && !empty($this->empresa) && is_array($this->empresa)) {
            $where[] = ['id_admin_empresa', 'in', $ormHelper->mudarListaUuidParaId($this->empresa)];
        } else {
            $where[] = ['id_admin_empresa', $this->idEmpresa];
        }

        if ($subempresa && !empty($this->subempresa) && validarUuid($this->subempresa, false)) {
            $idSubempresa = $ormHelper->pegarIdPeloUuid(
                $this->subempresa,
                'Há Subempresa informada não foi encontrada',
                'Subempresa inválida!'
            );
            $where[] = ['id_admin_subempresa', $idSubempresa];
        } elseif ($subempresa && !empty($this->subempresa) && is_array($this->subempresa)) {
            $where[] = ['id_admin_subempresa', 'in', $ormHelper->mudarListaUuidParaId($this->subempresa)];
        } elseif ($subempresa && empty($this->subempresa) && !empty($this->idSubempresa) && $this->idSubempresa !== 0) {
            $where[] = ['id_admin_subempresa', $this->idSubempresa];
        } else {
            $where[] = [
                'OR',
                ['id_admin_subempresa', 'null'],
                ['id_admin_subempresa', '0']
            ];
        }

        if ($dataAcesso && $this->dataInicial->valido() && $this->dataFinal->valido()) {
            $where[] = $this->dataIntervalo($colunaTabela, $this->dataInicial, $this->dataFinal);
        } elseif($dataAcesso && $this->dataInicial->valido()) {
            $where[] = $this->dataIntervalo($colunaTabela, $this->dataInicial, $this->dataInicial);
        } elseif($dataAcesso && $this->dataFinal->valido()) {
            $where[] = $this->dataIntervalo($colunaTabela, $this->dataFinal, $this->dataFinal);
        }
        return $where;
    }

    private function dataIntervalo($colunaTabela, $inicio, $final): array {
        return [
            $colunaTabela, 'between', [
                $inicio->banco() . ' 00:00:00', $final->banco() . ' 23:59:59'
            ]
        ];
    }
}
