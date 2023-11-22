<?php

namespace App\Models\Api\Demanda;

use ORM\ORM;
use App\Classes\DemandaTarefa\Tipo;
use App\Classes\DemandaTarefa\Helper;
use App\Classes\DemandaTarefa\Status;

final class TarefaModel extends ORM
{
    protected string $ormTabela = TABELA_DEMANDA_TAREFA;

    public function __construct(
        private DemandaEntity $Demanda
    ) {
        parent::__construct();
    }

    public function pegarListaTarefa(): array
    {
        $lista = $this
            ->campo([
                'uuid', 'titulo', 'texto', 'tipo', 'data_criacao', 'data_atualizacao', 'data_producao_inicio',
                'data_producao_final', 'like', 'status'
            ])
            ->where([
                ['id_demanda_dado', $this->Demanda->get('id')],
                ['status', 'in', Helper::STATUS_LIBERADO]
            ])
            ->order('status', 'ASC')
            ->tabela(TABELA_USUARIO_EQUIPE)
            ->leftJoin('id', 'id_usuario_equipe')
            ->campo(['uuid'], 'usuario')
            ->read();

        return $this->montarRetorno($lista);
    }

    private function montarRetorno(array $lista): array
    {
        $retorno = [];
        $Tipo = new Tipo();
        $Status = new Status();
        foreach ($lista as $r) {
            $retorno[] = object([
                'id'                       => $r->uuid,
                'titulo'                   => $r->titulo,
                'texto'                    => $r->texto,
                'equipe'                   => $r->usuario_uuid,
                'tipo'                     => $Tipo->indice($r->tipo),
                'data_criacao'             => $r->data_criacao,
                'data_atualizacao'         => $r->data_atualizacao,
                'data_producao_inicio'     => $r->data_producao_inicio,
                'data_producao_final'      => $r->data_producao_final,
                'teste'                    => jsonDecode($r->like, true, true),
                'status'                   => $Status->indice($r->status)
            ]);
        }
        return $retorno;
    }
}
