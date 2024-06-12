<?php

namespace App\Models\Api\Parceiro\Externo\Trait;

use Modules\Data;
use App\Classes\ParceiroLoja\Status;

trait Propriedade
{
    public string $equipe;
    public Data $data_criacao_de;
    public Data $data_criacao_ate;
    public Status $status;
}
