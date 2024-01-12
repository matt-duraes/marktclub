<?php

namespace App\Models\Api\Demanda;

use Helpers\OrmHelper;
use ORM\ORM;
use App\Classes\DemandaDado\Area;
use App\Classes\DemandaDado\Tipo;
use App\Classes\DemandaDado\Ordem;
use System\Trait\Model\OrdemTrait;
use App\Classes\DemandaDado\Status;
use App\Classes\DemandaTarefa\Tipo as DemandaTarefaTipo;
use App\Models\Api\Demanda\Trait\EquipeTrait;
use App\Models\Api\Demanda\Trait\EmpresaTrait;

final class DemandaModel extends ORM
{
    use EquipeTrait;
    use EmpresaTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_DEMANDA_DADO;

    public function __construct(
        protected Status $status,
        protected Ordem $ordem,
        protected Area $area,
        protected Tipo $tipo,
        protected ?string $tarefa_tipo,
        protected ?string $empresa
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
                'tarefa_tipo', 'data_criacao', 'data_atualizacao', 'com_prazo', 'data_entrega', 'status'
            ])
            ->where($this->montarWhere())
            ->order($this->pegarOrdem())
            ->tabela(TABELA_USUARIO_EQUIPE)
            ->join('id', 'id_usuario_equipe')
            ->campo(['uuid'], 'usuario')
            ->read();

        return $this->montarRetorno($lista);
    }

    private function validarRequest()
    {
        if ($this->status->vazio()) {
            mensagemErro('Campo obrigatório!', 'Você deve passar um status para busca.');
        } elseif (!$this->status->valido()) {
            mensagemErro('Campo inválido!', 'Você deve passar um status válido para a busca.');
        } elseif ($this->area->vazio()) {
            mensagemErro('Campo obrigatório!', 'Você deve passar a área para busca.');
        } elseif (!$this->area->valido()) {
            mensagemErro('Campo inválido!', 'Você deve passar uma área válida para a busca.');
        } elseif ($this->ordem->vazio()) {
            mensagemErro('Campo obrigatório!', 'Você deve passar uma ordem para busca.');
        } elseif (!$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'Você deve passar uma ordem válido para a busca.');
        }
    }

    private function montarWhere()
    {
        $where = [];

        $where[] = $this->pegarWhereStatus();

        if (!empty($this->tarefa_tipo)) {
            $tarefa_tipo = (new DemandaTarefaTipo($this->tarefa_tipo))->numero();
            $where[] = ['tarefa_tipo', 'json', $tarefa_tipo];
        }

        if (!empty($this->empresa)) {
            $empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->pegarIdPeloUuid($this->empresa);
            $where[] = ['id_admin_empresa', $empresa];
        }

        if ($this->tipo->valido()) {
            $where[] = ['tipo', $this->tipo->numero()];
        }

        return $where;
    }

    private function pegarWhereStatus()
    {
        $status = $this->status;
        if ($status->indice() == Status::CONCLUIDA) {
            return [
                ['status', $status->numero()],
                ['area', $this->area->numero()],
                ['data_atualizacao', '>=', dataRemover(agora(), 10, 'dias')]
            ];
        }

        return [
            ['status', $status->numero()],
            ['area', $this->area->numero()]
        ];
    }

    private function montarRetorno(array $lista): array
    {
        $retorno = [];
        foreach ($lista as $r) {
            $retorno[$r->id] = [
                'id'               => $r->uuid,
                'equipe'           => $r->usuario_uuid,
                'titulo'           => $r->titulo,
                'tipo'             => (new Tipo($r->tipo))->indice(),
                'data_criacao'     => $r->data_criacao,
                'data_entrega'     => $r->com_prazo == 1 ? $r->data_entrega : '',
                'status'           => (new Status($r->status))->indice()
            ];
        }

        return array_values($retorno);
    }
}
