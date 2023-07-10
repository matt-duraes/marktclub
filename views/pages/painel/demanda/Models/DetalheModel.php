<?php

namespace Painel\Demanda\Models;

final class DetalheModel
{
    public function montarDado($dado)
    {
        $tarefa = [];
        $dado->data_criacao = dataBr($dado->data_criacao);
        $dado->data_entrega = !empty($dado->data_entrega) ? dataBr($dado->data_entrega) : '';
        foreach ($dado->tarefa as $r) {
            $r->tempo_estimado = $this->calcularTempo($r->minuto_producao_estimada);
            $r->tempo_real = $this->calcularTempo($r->minuto_producao_real);
            $tarefa[] = $r;
        }
        $dado->tarefa = $tarefa;
        return $dado;
    }

    private function calcularTempo($tempo)
    {
        if (empty($tempo)) {
            return '';
        } elseif ($tempo < 60) {
            return $tempo . ' minutos';
        }
        $tempo = $tempo / 60;
        $texto = $tempo < 2 ? 'hora' : 'horas';
        if ($tempo > 24) {
            $tempo = $tempo / 24;
            $texto = $tempo < 2 ? 'dia' : 'dias';
        }
        return is_int($tempo) ? $tempo . ' ' . $texto : '+' . (int)$tempo . ' ' . $texto;
    }
}
