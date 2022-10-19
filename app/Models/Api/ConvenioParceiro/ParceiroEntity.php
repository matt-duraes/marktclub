<?php

namespace App\Models\Api\ConvenioParceiro;

use ORM\Entity;

final class ParceiroEntity extends Entity
{
    protected string $_tabela = TABELA_PARCEIRO_NOVO;

    protected function getId()
    {
        return $this->prop('id');
    }
}
