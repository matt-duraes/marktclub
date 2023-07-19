<?php

namespace Painel\Demanda\Models;

use Helpers\ApiHelper;

trait TarefaTrait
{
    private function salvarTarefa(
        string $tipo,
        string $titulo,
        string $texto,
        ?string $equipe = null,
        ?int $tempo = null
    ) {
        $Api = new ApiHelper(token: true);
        $dado = [
            'demanda' => $this->Demanda->dado->id,
            'tipo'    => $tipo,
            'titulo'  => $titulo,
            'texto'   => $texto
        ];
        if (!empty($tempo)) {
            $dado['minuto_producao_estimada'] = $tempo;
        }
        if (!empty($equipe)) {
            $dado['equipe'] = $equipe;
        }
        $Api->body($dado)->post('/demanda-tarefa');
        if (!empty($equipe) && !in_array($equipe, $this->listaNotificacao)) {
            $this->listaNotificacao[] = $equipe;
        }
    }
}
