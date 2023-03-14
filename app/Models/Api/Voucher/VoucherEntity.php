<?php

namespace App\Models\Api\Voucher;

use ORM\Entity;
use Modules\Data;
use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\ConvenioParceiro\ParceiroEntity;

final class VoucherEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $_tabela = TABELA_SOLICITACAO_VOUCHER;
    protected array $_insert = [
        'cod', 'usuario', 'tipo', 'vinculo', 'codigo', 'data_vencimento',
        'empresa' => '->idEmpresa',
    ];
    protected array $_salvar = ['status'];
    protected array $_update = ['data_validacao'];

    private ?int $idEmpresa;
    public Status $status;
    protected string $cod;
    protected int $usuario;
    protected string $vinculo;
    public Data $data_vencimento;

    public function __construct(
        private ?ParceiroEntity $Parceiro = null,
        private ?ClienteEntity $Usuario = null,
        protected ?Tipo $tipo = null
    ) {
        parent::__construct();
        $this->setarIdEmpresa();
    }

    protected function regraInsert()
    {
        $this->cod = uuid();
        $this->status = new Status(Status::CRIADO);
        $this->usuario = $this->Usuario->get('id');
        $this->vinculo = $this->Parceiro->id;
        $this->data_vencimento = new Data(dataAdicionar(hoje(), $this->Parceiro->prazo_voucher, 'dias'));
    }
}
