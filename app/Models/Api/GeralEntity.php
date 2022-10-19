<?php

namespace App\Models\Api;

use ORM\Entity;
use App\Models\Api\UsuarioCliente\ClienteEntity;

abstract class GeralEntity extends Entity
{
    protected int $idEmpresa;
    protected ?int $idUsuario = null;

    public function __construct()
    {
        parent::__construct();

        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado.');
        }

        $this->idEmpresa = TOKEN['empresa']->get('id');
        $this->idUsuario = array_key_exists('usuario', TOKEN) && is_object(TOKEN['usuario']) ?
            TOKEN['usuario']->get('id') :
            null;
        $this->_wherePadrao = ['id_admin_empresa', $this->idEmpresa];
    }
}
