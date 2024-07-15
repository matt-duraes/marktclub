<?php

namespace App\Models\Api\Demanda\Dado;

use ORM\ORM;
use App\Classes\DemandaDado\Status;
use App\Models\Api\Demanda\Sprint\Demanda\AtivaModel;

final class NaoFinalizadoModel extends ORM
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
        foreach ($this->buscarDemanda() as $r) {
            $id[] = $r->uuid;
        }
        return $id;
    }

    private function buscarDemanda()
    {
        $id = (new AtivaModel())->pegarId();
        if (empty($id)) {
            return [];
        }
        return $this
            ->campo(['uuid', 'titulo'])
            ->where([
                ['uuid', 'in', $id],
                ['status', 'in', Status::GERAL]
            ])
            ->read();
    }
}
