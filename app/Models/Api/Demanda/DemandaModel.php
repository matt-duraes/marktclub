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
            ->leftJoin('id_demanda_dado', 'id')
            ->campo(['id', 'id_usuario_equipe', 'tipo', 'status'], 'tarefa')
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
        if ($status->indice() == 'concluida') {
            return [
                ['status', $status->numero()],
                ['data_atualizacao', '>=', dataRemover(agora(), 10, 'dias')]
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
                if (!empty($r->tarefa_id)) {
                    $retorno[$r->id]['tarefa_total']++;
                }
                if (!empty($r->tarefa_status) && $r->tarefa_status == 2) {
                    $retorno[$r->id]['tarefa_andamento']++;
                }
                if (!empty($r->tarefa_status) && $r->tarefa_status == 3) {
                    $retorno[$r->id]['tarefa_concluida']++;
                }
                $equipe = $this->pegarUsuarioEquipe($r->tarefa_id_usuario_equipe);
                if (!empty($equipe->id) && !in_array($r->id . $r->tarefa_id_usuario_equipe, $equipeJaExiste)) {
                    $retorno[$r->id]['equipe'][] = $equipe;
                    $equipeJaExiste[] = $r->id . $r->tarefa_id_usuario_equipe;
                }
                if (!empty($area) && !in_array($r->id . $area, $areaJaExiste)) {
                    $retorno[$r->id]['area'][] = $area;
                    $areaJaExiste[] = $r->id . $area;
                }
                continue;
            }
            if (!empty($area)) {
                $areaJaExiste[] = $r->id . $area;
            }
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
                'area' => !empty($area) ? [$area] : [],
                'titulo' => $r->titulo,
                'tipo' => (new Tipo($r->tipo))->indice(),
                'tarefa_total' => !empty($r->tarefa_id) ? 1 : 0,
                'tarefa_andamento' => !empty($r->tarefa_status) && $r->tarefa_status == 2 ? 1 : 0,
                'tarefa_concluida' => !empty($r->tarefa_status) && $r->tarefa_status == 3 ? 1 : 0,
                'status' => (new Status($r->status))->indice()
            ];
        }

        return array_values($retorno);
    }
}
