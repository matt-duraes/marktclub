<?php

namespace App\Models\Api\Demanda\Dado;

use ORM\ORM;
use App\Classes\DemandaDado\Status;
use App\Models\Api\Demanda\Sprint\Demanda\AtivaModel;

final class NaSprintModel extends ORM
{
    protected string $ormTabela = TABELA_DEMANDA_DADO;

    public function naoFinalizada()
    {
        $retorno = [];
        foreach ($this->buscarDemanda() as $r) {
            $retorno[] = [
                'id'     => $r->uuid,
                'titulo' => $r->titulo
            ];
        }
        return $retorno;
    }

    public function idNaoFinalizada()
    {
        $id = [];
        foreach ($this->buscarDemanda(['id']) as $r) {
            $id[] = $r->id;
        }
        return $id;
    }

    private function buscarDemanda(array $campo = ['uuid', 'titulo'])
    {
        $id = (new AtivaModel())->pegarId();
        if (empty($id)) {
            return [];
        }
        return $this
            ->campo($campo)
            ->where([
                ['uuid', 'in', $id],
                ['status', 'in', Status::GERAL]
            ])
            ->read();
    }
}
