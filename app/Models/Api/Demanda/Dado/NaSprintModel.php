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
