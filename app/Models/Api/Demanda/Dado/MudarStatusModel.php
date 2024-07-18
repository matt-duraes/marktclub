<?php

namespace App\Models\Api\Demanda\Dado;

use ORM\ORM;
use App\Classes\DemandaDado\Status;

final class MudarStatusModel extends ORM
{
    protected string $ormTabela = TABELA_DEMANDA_DADO;

    public function __construct(string|array $id, Status $status)
    {
        parent::__construct();
        $id = is_string($id) ? [$id] : $id;
        $this->dado(['status' => $status])->where(['uuid', 'in', $id])->update();
    }
}
