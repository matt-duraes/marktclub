<?php

namespace App\Models\Api\Trait;

trait ValidarEmpresaTrait
{
    private function validarEmpresa()
    {
        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado no Model.');
        }
        $this->idEmpresa = TOKEN['empresa']->get('id');
        $this->whereEmpresa = $this->idEmpresa;
        $this->idUsuario = array_key_exists('usuario', TOKEN) && is_object(TOKEN['usuario']) ?
            TOKEN['usuario']->get('id') : null;
    }
}
