<?php

namespace App\Models\Api\SolicitacaoVoucher\Trait;

use App\Models\Api\AdminEmpresa\EmpresaEntity;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\ConvenioParceiro\ParceiroEntity;

trait CodigoBuscarTrait
{
    protected function regraPosBuscar()
    {
        if (empty($this->id_usuario_cliente)) {
            return;
        }
        $this->Usuario = new ClienteEntity(validarToken: false);
        $this->Usuario->_id($this->id_usuario_cliente);

        $this->Parceiro = new ParceiroEntity;
        $this->Parceiro->_id($this->id_parceiro_loja);

        $this->Empresa = new EmpresaEntity();
        $this->Empresa->_id($this->id_admin_empresa);
    }
}
