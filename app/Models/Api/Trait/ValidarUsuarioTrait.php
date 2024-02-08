<?php

namespace App\Models\Api\Trait;

trait ValidarUsuarioTrait
{
    private ?int $idUsuario = null;

    private function setarIdUsuario(): void
    {
        $this->verificarSeExisteToken();
        $this->idUsuario = array_key_exists('usuario', TOKEN) && !vazio(TOKEN['usuario'])
            ? TOKEN['usuario']->id
            : null;
    }

    private function verificarSeExisteToken(): void
    {
        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado no Model.');
        }
    }
}
