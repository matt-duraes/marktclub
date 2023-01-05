<?php

namespace App\Models\Api\Demanda;

use ORM\ORM;
use App\Classes\DemandaDado\Tipo;
use App\Classes\DemandaDado\Ordem;
use App\Classes\DemandaDado\Status;
use App\Classes\DemandaTarefa\Tipo as Area;
use App\Models\Api\Demanda\Trait\EquipeTrait;
use App\Models\Api\Demanda\Trait\EmpresaTrait;

final class DemandaModel extends ORM
{
    use EquipeTrait;
    use EmpresaTrait;

    protected string $_tabela = TABELA_DEMANDA_DADO;

    public function __construct(
        private Status $status,
        private Ordem $ordem
    ) {
        parent::__construct();
        $this->validarRequest();
    }

    public function listarDados(): array
    {
        $this->validarRequest();
        $lista = $this
            ->campo([
                'uuid', 'id', 'id_usuario_equipe', 'id_admin_empresa', 'titulo', 'tipo',
                'data_criacao', 'data_atualizacao', 'status'
            ])
            ->where($this->montarWhere())
            ->order($this->ordem)
            ->tabela(TABELA_DEMANDA_TAREFA)
            ->join('id_demanda_dado', 'id')
            ->campo(['id_usuario_equipe', 'tipo'], 'tarefa')
            ->read();

        return $this->montarRetorno($lista);
    }

    private function validarRequest()
    {
        if ($this->status->vazio()) {
            mensagemErro('Campo obrigatório!', 'Você deve passar um status para busca.');
        } else if (!$this->status->valido()) {
            mensagemErro('Campo inválido!', 'Você deve passar um status válido para a busca.');
        } else if ($this->ordem->vazio()) {
            mensagemErro('Campo obrigatório!', 'Você deve passar uma ordem para busca.');
        } else if (!$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'Você deve passar uma ordem válido para a busca.');
        }
    }

    private function montarWhere()
    {
        $status = $this->status;
        if ($status->indice() == 'finalizada') {
            return [
                ['status', $status->numero()],
                ['data_atualizacao', '<', dataRemover(agora(), 10, 'dias')]
            ];
        }
        return ['status', $status->numero()];
    }

    private function montarRetorno(array $lista): array
    {
        $demandaJaExiste = [];
        $equipeJaExiste = [];
        $areaJaExiste = [];

        $retorno = [];
        foreach ($lista as $r) {
            $area = (new Area($r->tarefa_tipo))->indice();

            if (in_array($r->id, $demandaJaExiste)) {
                $retorno[$r->id]['tarefa']++;
                $equipe = $this->pegarUsuarioEquipe($r->tarefa_id_usuario_equipe);
                if (!empty($equipe->id) && !in_array($r->id . $r->tarefa_id_usuario_equipe, $equipeJaExiste)) {
                    $retorno[$r->id]['equipe'][] = $equipe;
                    $equipeJaExiste[] = $r->id . $r->tarefa_id_usuario_equipe;
                }
                if (!in_array($r->id . $area, $areaJaExiste)) {
                    $retorno[$r->id]['area'][] = $area;
                    $areaJaExiste[] = $r->id . $area;
                }
                continue;
            }
            $areaJaExiste[] = $r->id . $area;
            $demandaJaExiste[] = $r->id;

            $dono = $this->pegarUsuarioEquipe($r->id_usuario_equipe);
            $equipe = $this->pegarUsuarioEquipe($r->tarefa_id_usuario_equipe);
            if (!empty($equipe->id)) {
                $equipeJaExiste[] = $r->id . $r->tarefa_id_usuario_equipe;
            }

            $retorno[$r->id] = [
                'id' => $r->uuid,
                'dono' => $dono,
                'empresa' => $this->pegarEmpresa($r->id_admin_empresa),
                'equipe' => !empty($equipe->id) ? [$equipe] : [],
                'tarefa' => 1,
                'area' => [$area],
                'titulo' => $r->titulo,
                'tipo' => (new Tipo($r->tipo))->indice(),
                'status' => (new Status($r->status))->indice()
            ];
        }

        return array_values($retorno);
    }
}
