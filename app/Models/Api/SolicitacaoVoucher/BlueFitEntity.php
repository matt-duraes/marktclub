<?php

namespace App\Models\Api\SolicitacaoVoucher;

use ORM\Entity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\ConvenioParceiro\ParceiroEntity;

final class BlueFitEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $_tabela = '';

    private int $idEmpresa;

    public function __construct(
        private ?ParceiroEntity $Parceiro = null,
        private ?ClienteEntity $Usuario = null
    ) {
        parent::__construct();
        $this->validarEmpresa('empresa');
    }
}
