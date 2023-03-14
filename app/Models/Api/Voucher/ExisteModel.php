<?php

namespace App\Models\Api\Voucher;

use ORM\ORM;
use App\Classes\SolicitacaoVoucher\Tipo;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\ConvenioParceiro\ParceiroEntity;

final class ExisteModel extends ORM
{
    use ValidarEmpresaTrait;

    protected string $_tabela = TABELA_SOLICITACAO_VOUCHER;

    private VoucherEntity $Voucher;
    private bool $existe = false;
    private ?int $idEmpresa;

    /**
     * @param   ParceiroEntity  $Parceiro   Parceiro do voucher
     * @param   Tipo            $Tipo       Tipo do parceiro
     */
    public function __construct(
        private ParceiroEntity $Parceiro,
        private ClienteEntity $Usuario,
        private Tipo $Tipo
    ) {
        parent::__construct();
        $this->setarIdEmpresa();
        $this->buscarVoucher();
    }

    private function buscarVoucher()
    {
        $Voucher = new VoucherEntity();
        try {
            $Voucher->buscar([
                ['empresa', $this->idEmpresa],
                ['usuario', $this->Usuario->get('id')],
                ['tipo', $this->Tipo->numero()],
                ['vinculo', 'in', [$this->Parceiro->id, $this->Parceiro->get('id')]],
                ['data_vencimento', '<=', hoje()]
            ]);
            $this->Voucher = $Voucher;
            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Verifica se o voucher já existe
     *
     * @return  bool
     */
    public function jaExiste(): bool
    {
        return $this->existe;
    }

    public function pegarVoucher()
    {
        return $this->Voucher;
    }
}
