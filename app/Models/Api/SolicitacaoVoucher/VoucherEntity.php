<?php

namespace App\Models\Api\SolicitacaoVoucher;

use ORM\Entity;
use CodigoTrait;
use Modules\Data;
use Modules\DataHora;
use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\ConvenioParceiro\ParceiroEntity;
use App\Models\Api\SolicitacaoVoucher\Trait\VoucherInsertTrait;

final class VoucherEntity extends Entity
{
    use ValidarEmpresaTrait;
    use VoucherInsertTrait;
    use CodigoTrait;

    protected string $_tabela = TABELA_SOLICITACAO_VOUCHER;
    protected array $_buscar = [
        'id_usuario_cliente' => 'usuario',
        'id_vinculo' => 'vinculo',
        'tipo', 'codigo', 'data_criacao', 'data_atualizacao', 'data_validacao', 'data_vencimento', 'status'
    ];

    protected DataHora $data_criacao;
    protected DataHora $data_atualizacao;
    protected Data $data_vencimento;
    protected DataHora $data_validacao;

    public Tipo $tipo;
    public Status $status;

    protected string $id_vinculo;
    protected int $id_usuario_cliente;

    private int $idEmpresa;

    public function __construct(
        private ?ParceiroEntity $Parceiro = null,
        private ?ClienteEntity $Usuario = null
    ) {
        parent::__construct();
        $this->validarEmpresa('empresa');
    }

    protected function regraPosBuscar()
    {
        $this->Usuario = new ClienteEntity(validarToken: false);
        $this->Usuario->_id($this->id_usuario_cliente);

        $this->Parceiro = new ParceiroEntity;
        $this->Parceiro->id($this->id_vinculo);
    }
}
