<?php

namespace App\Models\Painel\AppGeral;

use stdClass;
use ORM\Entity;

abstract class AppGeralEntity extends Entity implements PainelEntityInterface
{
    public function dadoEditar(): stdClass
    {
        return object([]);
    }

    public function dadoVisualizar(): stdClass
    {
        return object([]);
    }

    public function setarStatus(string|int $status): void
    {
    }
}
