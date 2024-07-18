<?php

namespace App\Models\Api\Demanda\Sprint\Demanda;

use ORM\ORM;
use App\Classes\Demanda\Sprint\Status;

final class AtivaModel extends ORM
{
    protected string $ormTabela = TABELA_DEMANDA_SPRINT;

    public function pegarId()
    {
        return jsonDecode(
            $this->campo(['id_demanda'])->where(['status', 'in', Status::PUBLICADO])->primeiro(campo: 'id_demanda'),
            true,
            true
        );
    }
}
