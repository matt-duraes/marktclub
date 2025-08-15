<?php

namespace App\Models\Api\CampanhaVoucher;

use ORM\ORM;
use App\Classes\Comercial\Empresa\ID;

final class ValidarUsuarioTemVoucherModel extends ORM
{
    protected string $ormTabela = TABELA_CAMPANHA_VOUCHER;

    public bool $existe = false;

    public function __construct()
    {
        if(
            !defined('TOKEN') ||
            !array_key_exists('empresa', TOKEN) ||
            TOKEN['empresa'] !== ID::GEAP ||
            !array_key_exists('usuario', TOKEN) ||
            !object_key_exists('id', TOKEN['usuario']) ||
            empty(TOKEN['usuario'])
        ) {
            return;
        }
        $idEmpresa = TOKEN['empresa']->id;
        $idUsuario = TOKEN['usuario']->id;
        $this->validarUsuarioExiste($idEmpresa, $idUsuario);
    }

    private function validarUsuarioExiste(int $idEmpresa, int $idUsuario): void
    {
        $this->existe = $this->existe([
            ['id_admin_empresa', $idEmpresa],
            ['id_usuario_cliente', $idUsuario]
        ]);
    }
}
