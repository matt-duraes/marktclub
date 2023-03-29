<?php

namespace App\Models\Api\SolicitacaoVoucher\Trait;

use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;

trait VoucherBuscarTrait
{
    protected function regraPosBuscar()
    {
        $this->Usuario = new ClienteEntity(validarToken: false);
        $this->Usuario->_id($this->id_usuario_cliente);

        $this->Parceiro = new LojaEntity();
        $this->Parceiro->id($this->id_vinculo);

        $this->Construtor = new ConstrutorEntity();
        $this->Construtor->buscar([
            ['empresa', $this->id_admin_empresa],
            ['status', 'in', [1, 2]]
        ]);

        $this->qr_code = 'https://chart.apis.google.com/chart?cht=qr&chl=http://voucher.marktclub.com.br/validar/' . $this->codigo . '&chs=300x300';
        $this->montarTexto();
    }
}
