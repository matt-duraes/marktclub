<?php

namespace Painel\DemandaGeral\Models;

use Helpers\ApiHelper;
use PainelModel\Perfil\Equipe;
use App\Classes\DemandaTarefa\Tipo;
use App\Classes\DemandaTarefa\Status as DemandaTarefaStatus;

trait TarefaTrait
{
    private array $listaTarefa;

    private function salvarTarefa(
        string $tipo,
        string $titulo,
        string $texto,
        ?string $equipe = null,
        ?int $tempo = null,
        ?int $dificuldade = null
    ) {
        $Api = new ApiHelper(token: true);
        $dado = [
            'demanda' => $this->Demanda->dado->id,
            'tipo'    => $tipo,
            'titulo'  => $titulo,
            'texto'   => $texto,
        ];
        if (!empty($dificuldade)) {
            $dado['dificuldade'] = $dificuldade;
        }
        if (!empty($tempo)) {
            $dado['minuto_producao_estimada'] = $tempo;
        }
        if (!empty($equipe)) {
            $dado['equipe'] = $equipe;
        }
        $dado = $Api->body($dado)->post('/demanda-tarefa');
        if (!empty($equipe) && !in_array($equipe, $this->listaNotificacao)) {
            $this->listaNotificacao[] = $equipe;
        }

        $this->listaTarefa[] = $tipo;
    }

    private function montarTarefa($tarefa): array
    {
        $retorno = [];
        $Status = new DemandaTarefaStatus();
        $Tipo = new Tipo();
        $Equipe = new Equipe();
        foreach ($tarefa as $r) {
            $retorno[] = (object)[
                'id'           => $r->id,
                'equipe'       => $Equipe->unico($r->equipe),
                'like'         => $Equipe->lista($r->like ?? []),
                'dono'         => $r->equipe == sessao('USUARIO.id'),
                'titulo'       => $r->titulo,
                'texto'        => $r->texto,
                'tipo'         => $Tipo->nome($r->tipo),
                'tipo_valor'   => $r->tipo,
                'data_inicio'  => dataBr($r->data_producao_inicio),
                'data_final'   => dataBr($r->data_producao_final),
                'status_valor' => $Status->indice($r->status),
                'status'       => $Status->nome($r->status)
            ];
        }
        return array_reverse($retorno);
    }

    private function atualizarTarefaTipoDemanda()
    {
        $Api = new ApiHelper(token: true);
        $Api
            ->body([
                'tarefa_tipo' => $this->listaTarefa
            ])
            ->put('/demanda-dado/' . $this->Demanda->dado->id);
    }
}
