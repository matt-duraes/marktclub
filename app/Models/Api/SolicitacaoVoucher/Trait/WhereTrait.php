<?php

namespace App\Models\Api\SolicitacaoVoucher\Trait;

use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\Status;

trait WhereTrait
{
    protected function pegarWhere(): array
    {
        $where = $this->_wherePadrao;

        $Status = new Status($this->request->status);
        if ($Status->valido()) {
            $where[] = ['status', $Status->numero()];
        }

        $Tipo = new Tipo($this->request->tipo);
        if ($Tipo->valido()) {
            $where[] = ['tipo', $Tipo->numero()];
        }

        $dataCriacaoDe = $this->request->data_criacao_de;
        $dataCriacaoAte = $this->request->data_criacao_ate;
        if (validarDataDate($dataCriacaoDe) && validarDataDate($dataCriacaoAte)) {
            $where[] = [
                'data_criacao',
                'between',
                [dataBanco($dataCriacaoDe), dataBanco($dataCriacaoAte) . ' 23:59:59']
            ];
        } else if (validarDataDate($dataCriacaoDe)) {
            $where[] = ['data_criacao', '>=', dataBanco($dataCriacaoDe)];
        } else if (validarDataDate($dataCriacaoAte)) {
            $where[] = ['data_criacao', '<=', dataBanco($dataCriacaoAte) . ' 23:59:59'];
        }

        $dataValidacaoDe = $this->request->data_validacao_de;
        $dataValidacaoAte = $this->request->data_validacao_ate;
        if (validarDataDate($dataValidacaoDe) && validarDataDate($dataValidacaoAte)) {
            $where[] = [
                'data_criacao',
                'between',
                [dataBanco($dataValidacaoDe), dataBanco($dataValidacaoAte) . ' 23:59:59']
            ];
        } else if (validarDataDate($dataValidacaoDe)) {
            $where[] = ['data_criacao', '>=', dataBanco($dataValidacaoDe)];
        } else if (validarDataDate($dataValidacaoAte)) {
            $where[] = ['data_criacao', '<=', dataBanco($dataValidacaoAte) . ' 23:59:59'];
        }

        return $where;
    }
}
