<?php

namespace App\Models\Api\SolicitacaoVoucher\Trait;

use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\ConstrutorClube\ConstrutorEntity;

trait CodigoBuscarTrait
{
    protected function regraPosBuscar()
    {
        if (empty($this->id_usuario_cliente)) {
            return;
        }
        $this->Usuario = new ClienteEntity(validarToken: false);
        $this->Usuario->id($this->id_usuario_cliente);

        $this->Parceiro = new LojaEntity();
        $this->Parceiro->id($this->id_parceiro_loja);

        $this->Construtor = new ConstrutorEntity();
        $this->Construtor->buscar([
            ['id_admin_empresa', $this->id_admin_empresa],
            ['status', 'in', [1, 2]]
        ]);

        $this->qr_code = 'https://qrcode.youhuul.com/voucher/' . $this->codigo;

        $this->montarTexto();
    }
}
