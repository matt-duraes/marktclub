<?php

namespace App\Models\Api\Demanda;

use ORM\ORM;
use Modules\DataHora;
use App\Classes\DemandaTarefa\Tipo;
use App\Classes\DemandaTarefa\Status;
use App\Models\Api\Demanda\Trait\EquipeTrait;

final class TarefaModel extends ORM
{
    use EquipeTrait;

    protected string $_tabela = TABELA_DEMANDA_TAREFA;

    public function __construct(
        private DemandaEntity $Demanda
    ) {
        parent::__construct();
    }

    public function pegarListaTarefa(): array
    {
        $lista = $this
            ->where([
                ['id_demanda_dado', $this->Demanda->get('id')]
            ])->order('status', 'ASC')->read();

        return $this->montarRetorno($lista);
    }

    private function montarRetorno(array $lista): array
    {
        $retorno = [];
        $Tipo = new Tipo();
        $Status = new Status();
        foreach ($lista as $r) {
            $retorno[] = object([
                'id' => $r->uuid,
                'titulo' => $r->titulo,
                'texto' => $r->texto,
                'dev' => $this->pegarUsuarioEquipe($r->id_usuario_equipe),
                'tipo' => $Tipo->indice($r->tipo),
                'data_criacao' => $r->data_criacao,
                'data_atualizacao' => $r->data_atualizacao,
                'data_producao_inicio' => $r->data_producao_inicio,
                'data_producao_final' => $r->data_producao_final,
                'minuto_producao_estimada' => $r->minuto_producao_estimada,
                'minuto_producao_real' => $r->minuto_producao_real,
                'status' => $Status->indice($r->status)
            ]);
        }
        return $retorno;
    }

    public function verificarSeTodasAsTarefasEstaoConcluidas(): bool
    {
        $dado = $this->campo(['status'])->where(['id_demanda_dado', $this->Demanda->get('id')])->read();
        if (!$dado) {
            return false;
        }
        $Status = new Status();
        foreach ($dado as $r) {
            if ($Status->indice($r->status) == 'concluida') {
                continue;
            }
            return false;
        }
        return true;
    }
}
