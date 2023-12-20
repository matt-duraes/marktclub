<?php

namespace App\Models\Api\SolicitacaoVoucher;

use ORM\Entity;
use Modules\Data;
use Modules\DataHora;
use App\Classes\SolicitacaoCodigo\Status;
use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\ConstrutorClube\ConstrutorEntity;
use App\Models\Api\SolicitacaoVoucher\Trait\TextoTrait;
use App\Models\Api\SolicitacaoVoucher\Trait\CodigoBuscarTrait;
use App\Models\Api\SolicitacaoVoucher\Trait\CodigoInsertTrait;
use App\Models\Api\SolicitacaoVoucher\Interface\VoucherInterface;

final class CodigoEntity extends Entity implements VoucherInterface
{
    use CodigoInsertTrait;
    use CodigoBuscarTrait;
    use TextoTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_CODIGO;
    protected array $ormUpdate = [
        'id_admin_empresa', 'id_usuario_cliente', 'id_parceiro_loja', 'data_emissao', 'data_vencimento', 'status'
    ];
    protected array $ormBuscar = [
        'id_admin_empresa', 'id_usuario_cliente', 'id_parceiro_loja', 'data_emissao',
        'data_criacao', 'data_vencimento', 'status',
        'codigo'
    ];
    public string $codigo;
    public DataHora $data_emissao;
    public Data $data_vencimento;
    public Status $status;
    public string $texto_desconto = '';
    public string $texto_voucher = '';
    public string $texto_juridico = '';
    public string $texto_validar = '';
    public string $qr_code;
    public ConstrutorEntity $Construtor;
    protected string $id_admin_empresa;
    protected string $id_usuario_cliente;
    protected string $id_parceiro_loja;

    public function __construct(
        public ?LojaEntity $Parceiro = null,
        public ?ClienteEntity $Usuario = null
    ) {
        parent::__construct();
    }
}
