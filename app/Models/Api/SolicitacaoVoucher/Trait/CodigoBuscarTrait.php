<?php

namespace App\Models\Api\SolicitacaoVoucher\Trait;

use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;

trait CodigoBuscarTrait
{
    protected function regraPosBuscar()
    {
        if (empty($this->id_usuario_cliente)) {
            return;
        }
        $this->Usuario = new ClienteEntity(validarToken: false);
        $this->Usuario->_id($this->id_usuario_cliente);

        $this->Parceiro = new LojaEntity();
        $this->Parceiro->_id($this->id_parceiro_loja);

        $this->Construtor = new ConstrutorEntity();
        $this->Construtor->buscar([
            ['empresa', $this->id_admin_empresa],
            ['status', 'in', [1, 2]]
        ]);

        $this->montarTexto();
    }
}
