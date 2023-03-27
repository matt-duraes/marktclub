<?php

namespace App\Models\Api\SolicitacaoVoucher;

use ORM\Entity;
use Modules\Data;
use Modules\DataHora;
use App\Classes\SolicitacaoCodigo\Status;
use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;
use App\Models\Api\SolicitacaoVoucher\Trait\TextoTrait;
use App\Models\Api\SolicitacaoVoucher\Trait\CodigoBuscarTrait;
use App\Models\Api\SolicitacaoVoucher\Trait\CodigoInsertTrait;
use App\Models\Api\SolicitacaoVoucher\Interface\VoucherInterface;

final class CodigoEntity extends Entity implements VoucherInterface
{
    use ValidarEmpresaTrait;
    use CodigoInsertTrait;
    use CodigoBuscarTrait;
    use TextoTrait;

    protected string $_tabela = TABELA_SOLICITACAO_CODIGO;
    protected array $_update = [
        'id_admin_empresa', 'id_usuario_cliente', 'id_parceiro_loja', 'data_emissao', 'data_vencimento', 'status'
    ];
    protected array $_buscar = [
        'id_admin_empresa', 'id_usuario_cliente', 'id_parceiro_loja', 'data_emissao',
        'data_criacao', 'data_vencimento', 'status',
        'codigo'
    ];

    private int $idEmpresa;

    public string $codigo;
    public DataHora $data_emissao;
    public Data $data_vencimento;
    public Status $status;

    public string $texto_desconto = '';
    public string $texto_voucher = '';
    public string $texto_juridico = '';
    public string $texto_validar = '';

    public ConstrutorEntity $Construtor;

    public function __construct(
        public ?LojaEntity $Parceiro = null,
        public ?ClienteEntity $Usuario = null
    ) {
        parent::__construct();
        $this->setarIdEmpresa();
    }
}
