<?php

namespace App\Models\Api\SolicitacaoVoucher;

use ORM\Entity;
use Modules\Data;
use Modules\DataHora;
use ORM\Buscar\BuscarTrait;
use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\ConvenioParceiro\ParceiroEntity;
use App\Models\Api\SolicitacaoVoucher\Trait\CodigoTrait;
use App\Models\Api\SolicitacaoVoucher\Trait\VoucherInsertTrait;
use App\Models\Api\SolicitacaoVoucher\Interface\VoucherInterface;

final class VoucherEntity extends Entity implements VoucherInterface
{
    use ValidarEmpresaTrait;
    use VoucherInsertTrait;
    use BuscarTrait;

    protected string $_tabela = TABELA_SOLICITACAO_VOUCHER;

    protected array $_buscar = [
        'id_usuario_cliente' => 'usuario',
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

    private int $idEmpresa;

    public function __construct(
        public ?ParceiroEntity $Parceiro = null,
        public ?ClienteEntity $Usuario = null
    ) {
        parent::__construct();
        $this->validarEmpresa('empresa');
    }
}
