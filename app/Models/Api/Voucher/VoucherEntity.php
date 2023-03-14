<?php

namespace App\Models\Api\Voucher;

use ORM\Entity;
use App\Classes\SolicitacaoVoucher\Tipo;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\ConvenioParceiro\ParceiroEntity;

final class VoucherEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $_tabela = TABELA_SOLICITACAO_VOUCHER;
    protected array $_salvar = [];

    private ?int $idEmpresa;

    public function __construct(
        private ?ParceiroEntity $Parceiro = null,
        private ?ClienteEntity $Usuario = null,
        protected ?Tipo $tipo = null
    ) {
        parent::__construct();
        $this->setarIdEmpresa();
    }

    protected function regraSalvar()
    {
    }
}
