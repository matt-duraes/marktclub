<?php

namespace App\Models\Api\SolicitacaoVoucher\Trait;

use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\Status;
use App\Classes\SolicitacaoVoucher\TipoUsuario;

trait ModelWhereTrait
{
    protected function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;

        $Status = new Status($this->request->status);
        if ($Status->valido()) {
            $where[] = ['status', $Status->numero()];
        }

        $Tipo = new Tipo($this->request->tipo);
        if ($Tipo->valido()) {
            $where[] = ['tipo', $Tipo->numero()];
        }

        $tipoUsuario = new TipoUsuario($this->request->tipo_usuario);
        if ($tipoUsuario->valido()) {
            $where[] = ['tipo_usuario', $Tipo->numero()];
        }

        $dataCriacaoDe = $this->request->data_criacao_de;
        $dataCriacaoAte = $this->request->data_criacao_ate;
        if (validarDataDate($dataCriacaoDe) && validarDataDate($dataCriacaoAte)) {
            $where[] = [
                'data_criacao',
                'between',
                [dataBanco($dataCriacaoDe), dataBanco($dataCriacaoAte) . ' 23:59:59']
            ];
        } elseif (validarDataDate($dataCriacaoDe)) {
            $where[] = ['data_criacao', '>=', dataBanco($dataCriacaoDe)];
        } elseif (validarDataDate($dataCriacaoAte)) {
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
        } elseif (validarDataDate($dataValidacaoDe)) {
            $where[] = ['data_criacao', '>=', dataBanco($dataValidacaoDe)];
        } elseif (validarDataDate($dataValidacaoAte)) {
            $where[] = ['data_criacao', '<=', dataBanco($dataValidacaoAte) . ' 23:59:59'];
        }

        return $where;
    }
}
