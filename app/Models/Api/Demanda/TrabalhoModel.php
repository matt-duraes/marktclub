<?php

namespace App\Models\Api\Demanda;

use ORM\ORM;

final class TrabalhoModel extends ORM
{
    protected string $_tabela = TABELA_DEMANDA_TRABALHO;

    public function darBaixaTrabalhoAntigos(int $id)
    {
        $lista = $this
            ->campo(['id', 'data_criacao', 'data_trabalho'])
            ->where([
                ['status', 1],
                ['id_demanda_tarefa', $id]
            ])->read();
        if (!$lista) {
            return;
        }
        $this->atualizarStatusMinutoTrabalhado($lista);
    }

    private function atualizarStatusMinutoTrabalhado($lista)
    {
        foreach ($lista as $r) {
            $minuto = dataDiferencaMinuto($r->data_criacao, $r->data_trabalho);
            $this->dado([
                'minuto_trabalhado' => $minuto,
                'status' => 2
            ])->where(['id', $r->id])->update();
        }
    }

    public function atualizarTempoTotalTrabalho(int $id)
    {
        $dado = $this->campo(['minuto_trabalhado'])->where([
            ['status', 2],
            ['id_demanda_tarefa', $id]
        ])->read();

        if (!$dado) {
            return 0;
        }
        $total = 0;
        foreach ($dado as $r) {
            $total += $r->minuto_trabalhado;
        }

        $Tarefa = new TarefaEntity();
        $Tarefa->_id($id);
        $Tarefa->minuto_producao_real = $total;
        $Tarefa->salvar();
    }
}
