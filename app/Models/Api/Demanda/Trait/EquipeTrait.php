<?php

namespace App\Models\Api\Demanda\Trait;

use App\Models\Api\UsuarioEquipe\PerfilModel;

trait EquipeTrait
{
    private array $equipeLista = [];

    private function pegarUsuarioEquipe($equipe)
    {
        if (!array_key_exists($equipe, $this->equipeLista)) {
            $Perfil = new PerfilModel();
            $this->equipeLista[$equipe] = $Perfil->pegarDado($equipe);
        }

        return $this->equipeLista[$equipe];
    }
}
