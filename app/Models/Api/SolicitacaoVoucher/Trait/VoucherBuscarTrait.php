<?php

namespace App\Models\Api\SolicitacaoVoucher\Trait;

use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\ConvenioParceiro\ParceiroEntity;

trait VoucherBuscarTrait
{
    protected function regraPosBuscar()
    {
        $this->Usuario = new ClienteEntity(validarToken: false);
        $this->Usuario->_id($this->id_usuario_cliente);

        $this->Parceiro = new ParceiroEntity;
        $this->Parceiro->id($this->id_vinculo);
    }
}
