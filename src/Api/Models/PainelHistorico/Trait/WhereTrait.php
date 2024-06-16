<?php

namespace ApiModel\PainelHistorico\Trait;

trait WhereTrait
{
    protected function pegarWhere(): array
    {
        $where = [
            ['status', 1],
            ['id_relacionado', 'like', '%"' . $this->relacionado . '"%'],
            ['app', 'like', '%"' . str_replace('-', '_', $this->app) . '"%']
        ];
        $dataDe = $this->propriedadeExiste('data_de') ? $this->data_de : '';
        if (!empty($dataDe) && validarData($dataDe)) {
            $where[] = ['data_criacao', '>=', dataBanco($dataDe)];
        }
        $dataAte = $this->propriedadeExiste('data_ate') ? $this->data_ate : '';
        if (!empty($dataAte) && validarData($dataAte)) {
            $where[] = ['data_criacao', '<=', dataBanco($dataAte) . ' 23:59:59'];
        }
        $pesquisa = $this->propriedadeExiste('pesquisa') ? $this->pesquisa : '';
        if (!empty($pesquisa)) {
            $where[] = ['mensagem', 'like', '%' . $pesquisa . '%'];
        }
        return $where;
    }
}
