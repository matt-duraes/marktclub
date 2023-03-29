<?php

namespace App\Models\Api\SolicitacaoVoucher;

use ORM\Entity;
use Modules\Data;
use Modules\DataHora;
use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\Status;
use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;
use App\Models\Api\SolicitacaoVoucher\Trait\TextoTrait;
use App\Models\Api\SolicitacaoVoucher\Trait\VoucherBuscarTrait;
use App\Models\Api\SolicitacaoVoucher\Trait\VoucherInsertTrait;
use App\Models\Api\SolicitacaoVoucher\Interface\VoucherInterface;

final class VoucherEntity extends Entity implements VoucherInterface
{
    use VoucherInsertTrait;
    use VoucherBuscarTrait;
    use TextoTrait;

    protected string $_tabela = TABELA_SOLICITACAO_VOUCHER;

    protected array $_buscar = [
        'id_usuario_cliente' => 'usuario',
        'id_admin_empresa' => 'empresa',
        'id_vinculo' => 'vinculo',
        'tipo', 'codigo', 'data_criacao', 'data_atualizacao', 'data_validacao', 'data_vencimento', 'status'
    ];
    protected array $_insert = [
        'empresa' => '->id_admin_empresa',
        'usuario' => '->id_usuario_cliente',
        'vinculo' => '->id_vinculo',
        'tipo', 'codigo', 'data_vencimento', 'status'
    ];

    public Data $data_vencimento;
    protected DataHora $data_validacao;
    public Tipo $tipo;
    public Status $status;

    protected string $id_vinculo;
    protected int $id_usuario_cliente;
    protected int $id_admin_empresa;

    public string $texto_desconto = '';
    public string $texto_voucher = '';
    public string $texto_juridico = '';
    public string $texto_validar = '';

    public string $qr_code;

    public ConstrutorEntity $Construtor;

    public function __construct(
        public ?LojaEntity $Parceiro = null,
        public ?ClienteEntity $Usuario = null
    ) {
        parent::__construct();
    }
}
