<?php

namespace App\Models\Api\Demanda\Dado;

use ORM\ORM;
use Helpers\OrmHelper;
use App\Classes\DemandaDado\Tipo as DemandaTipo;
use App\CLasses\DemandaTarefa\Tipo as TarefaTipo;

final class RelatorioSprintModel extends ORM
{
    private array $IdDemanda = [];
    public array $demanda = [];
    public array $tarefa = [];
    private array $equipe = [];
    private array $empresa = [];

    public function __construct(
        private array $uuid
    ) {
        $this->setarEquipe();
        $this->setarEmpresa();
        $this->buscarDemanda();
        $this->setarIdDemanda();
        $this->buscarTarefa();
    }

    private function setarEquipe()
    {
        $this->equipe = (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarSelect(indice: 'id', valor: 'nome_real', where: [
            ['id_admin_empresa', 1]
        ]);
    }

    private function setarEmpresa()
    {
        $this->empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->pegarSelect(indice: 'id', valor: 'titulo', where: [
            ['status', 1],
            ['id_admin_empresa', 'null']
        ]);
    }

    private function buscarDemanda()
    {
        $lista = (new OrmHelper(TABELA_DEMANDA_DADO))
            ->campo(['id', 'id_admin_empresa', 'id_usuario_equipe', 'tipo'])
            ->where(['uuid', 'in', $this->uuid])
            ->read();
        $retorno = [];
        foreach ($lista as $r) {
            $retorno[$r->id] = $r;
        }
        $this->demanda = $retorno;
    }

    private function setarIdDemanda()
    {
        foreach ($this->demanda as $r) {
            $this->IdDemanda[] = $r->id;
        }
    }

    private function buscarTarefa()
    {
        $lista = (new OrmHelper(TABELA_DEMANDA_TAREFA))
            ->campo(['id', 'id_demanda_dado', 'id_usuario_equipe', 'tipo', 'dificuldade'])
            ->where(['id_demanda_dado', 'in', $this->IdDemanda])
            ->read();

        $retorno = [];
        foreach ($lista as $r) {
            $demanda = $this->demanda[$r->id_demanda_dado];
            $retorno[] = [
                'empresa_valor' => $demanda->id_admin_empresa,
                'empresa_nome'  => $this->empresa[$demanda->id_admin_empresa] ?? '',
                'dono_valor'    => $demanda->id_usuario_equipe,
                'dono_nome'     => $this->equipe[$demanda->id_usuario_equipe] ?? '',
                'dev_valor'     => $r->id_usuario_equipe,
                'dev_nome'      => $this->equipe[$r->id_usuario_equipe] ?? '',
                'area_valor'    => $r->tipo,
                'area_nome'     => (new TarefaTipo($r->tipo))->nome(),
                'tipo_valor'    => $demanda->tipo,
                'tipo_nome'     => (new DemandaTipo($r->tipo))->nome(),
                'dificuldade'   => $r->dificuldade,
            ];
        }
        $this->tarefa = $retorno;
    }
}
