<?php

namespace App\Models\Api\SolicitacaoVoucher;

use ORM\Entity;
use Modules\Data;
use Modules\DataHora;
use App\Classes\SolicitacaoCodigo\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\AdminEmpresa\EmpresaEntity;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\ConvenioParceiro\ParceiroEntity;
use App\Models\Api\SolicitacaoVoucher\Trait\CodigoBuscarTrait;
use App\Models\Api\SolicitacaoVoucher\Trait\CodigoInsertTrait;
use App\Models\Api\SolicitacaoVoucher\Interface\VoucherInterface;

final class CodigoEntity extends Entity implements VoucherInterface
{
    use ValidarEmpresaTrait;
    use CodigoInsertTrait;
    use CodigoBuscarTrait;

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
    public EmpresaEntity $Empresa;

    public function __construct(
        public ?ParceiroEntity $Parceiro = null,
        public ?ClienteEntity $Usuario = null
    ) {
        parent::__construct();
        $this->setarIdEmpresa();
    }
}
