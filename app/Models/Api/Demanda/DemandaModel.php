<?php

namespace App\Models\Api\Demanda;

use ORM\ORM;
use Where\Where;
use Modules\Data;
use Modules\Botao;
use App\Classes\DemandaDado\Area;
use App\Classes\DemandaDado\Tipo;
use App\Classes\DemandaDado\Ordem;
use System\Trait\Model\OrdemTrait;
use App\Classes\DemandaDado\Status;
use App\Models\Api\Demanda\Trait\EquipeTrait;
use App\Models\Api\Demanda\Trait\EmpresaTrait;
use App\Models\Api\Demanda\Sprint\Demanda\AtivaModel;

final class DemandaModel extends ORM
{
    use EquipeTrait;
    use EmpresaTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_DEMANDA_DADO;

    public function __construct(
        public Status $status,
        public Ordem $ordem,
        public Area $area,
        public Data $data_entrega_de,
        public Data $data_entrega_ate,
        public Botao $sprint
    ) {
        parent::__construct();
        $this->validarRequest();
    }

    public function listarDados(): array
    {
        $this->validarRequest();
        $lista = $this
            ->campo([
                'uuid', 'id', 'id_usuario_equipe', 'id_admin_empresa', 'titulo', 'texto', 'tipo',
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
        } elseif ($this->status->real() != 'geral' && !$this->status->valido()) {
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

    private function montarWhere(): Where
    {
        $Where = new Where($this);
        $Where
            ->seIgual(propriedade: 'status', valor: 'geral', callback: function () use ($Where) {
                $Where->manual(['status', 'in', Status::GERAL]);
            })
            ->linha('status')
            ->linha('area')
            ->linha('tipo')
            ->dataDeAte('data_entrega')
            ->seBotao(propriedade: 'sprint', callback: function () use ($Where) {
                $id = (new AtivaModel())->pegarId();
                if (empty($id)) {
                    $id = [1];
                }
                $Where->manual(['uuid', 'in', $id]);
            });
        return $Where;
    }

    private function montarRetorno(array $lista): array
    {
        $retorno = [];
        foreach ($lista as $r) {
            $retorno[$r->id] = [
                'id'           => $r->uuid,
                'equipe'       => $r->usuario_uuid,
                'titulo'       => $r->titulo,
                'texto'        => $r->texto,
                'tipo'         => (new Tipo($r->tipo))->indice(),
                'data_criacao' => $r->data_criacao,
                'data_entrega' => $r->com_prazo == 1 ? $r->data_entrega : '',
                'status'       => (new Status($r->status))->indice()
            ];
        }

        return array_values($retorno);
    }
}
