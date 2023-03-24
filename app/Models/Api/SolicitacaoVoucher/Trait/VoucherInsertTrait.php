<?php

namespace App\Models\Api\SolicitacaoVoucher\Trait;

use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\Status;

trait VoucherInsertTrait
{
    public function regraInsert()
    {
        if ($this->verificarSeJaExisteVoucher()) {
            return;
        }
        $this->verificarLimiteVoucher();
        $this->setarValoresParaInsert();
    }
    private function setarValoresParaInsert()
    {
        $this->id_admin_empresa = $this->idEmpresa;
        $this->id_usuario_cliente = $this->Usuario->get('id');
        $this->id_vinculo = $this->Parceiro->id;
        $this->tipo = new Tipo(Tipo::VOUCHER);
        $this->codigo = $this->gerarCodigoUnico();
        $this->data_vencimento = dataAdicionar(hoje(), $this->Parceiro->prazo_voucher, 'dias');
        $this->status = new Status(Status::CRIADO);
    }

    private function verificarSeJaExisteVoucher(): bool
    {
        if (!$this->validarSeParceiroTemLimiteMaximo()) {
            return false;
        }

        $voucher = $this
            ->campo(['id', 'status'])
            ->where([
                ['data_criacao', 'between', [dataPrimeiroDiaMes(hoje()), dataUltimoDiaMes(hoje(), 'Y-m-d H:i:s')]],
                ['data_vencimento', '>=', hoje()],
                ['usuario', $this->Usuario->get('id')],
                ['empresa', $this->Usuario->id_admin_empresa],
                ['status', 'in', [1, 2]]
            ])->primeiro();

        if (empty($voucher)) {
            return false;
        } else if ($voucher->status == 2) {
            mensagemErro('Sem saldo!', 'Você já utilizou o voucher mensal desta parceria.');
        }

        $this->_id($voucher->id);
        $this->cancelarSalvar();

        return true;
    }

    private function verificarLimiteVoucher()
    {
        if ($this->validarSeParceiroTemLimiteMaximo()) {
            return;
        }

        $quantidade = $this->contar([
            ['data_criacao', 'between', [dataPrimeiroDiaMes(hoje()), dataUltimoDiaMes(hoje(), 'Y-m-d H:i:s')]],
            ['status', 'in', [1, 2]],
            ['empresa', $this->Usuario->id_admin_empresa]
        ]);

        if ($this->Parceiro->limite_voucher > $quantidade) {
            return;
        }

        mensagemErro(
            'Sem saldo!',
            'O saldo deste mês para esse parceiro expirou, abriremos um novo lote de vouchers no próximo mês.'
        );
    }

    private function validarSeParceiroTemLimiteMaximo(): bool
    {
        $limite = $this->Parceiro->limite_voucher;
        return is_int($limite) && !empty($limite);
    }
}
