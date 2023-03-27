<?php

namespace App\Models\Api\SolicitacaoVoucher;

use ORM\Entity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\ConvenioParceiro\ParceiroEntity;
use App\Models\Api\SolicitacaoVoucher\Interface\VoucherInterface;

final class CodigoEntity extends Entity implements VoucherInterface
{
    use ValidarEmpresaTrait;

    protected string $_tabela = TABELA_SOLICITACAO_CODIGO;

    private int $idEmpresa;

    public function __construct(
        private ?ParceiroEntity $Parceiro = null,
        private ?ClienteEntity $Usuario = null
    ) {
        parent::__construct();
        $this->setarIdEmpresa();
    }
}
