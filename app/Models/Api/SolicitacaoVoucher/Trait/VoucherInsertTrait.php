<?php

namespace App\Models\Api\SolicitacaoVoucher\Trait;

use Modules\Data;
use App\Classes\SolicitacaoVoucher\Status;
use App\Classes\SolicitacaoVoucher\TipoUsuario;
use App\Classes\UsuarioCliente\TipoUsuario as UsuarioClienteTipoUsuario;

trait VoucherInsertTrait
{
    protected function regraInsert()
    {
        if ($this->verificarSeJaExisteVoucherComPrazo()) {
            return;
        } elseif ($this->verificarSeTemVoucherCriadoAgora()) {
            return;
        }
        $this->verificarLimiteVoucher();
        $this->setarValoresParaInsert();
    }

    private function setarValoresParaInsert()
    {
        $this->id_admin_empresa = $this->Usuario->id_admin_empresa;
        $this->id_usuario_cliente = $this->Usuario->get('id');
        $this->tipo_usuario = $this->pegarTipoUsuario();
        $this->id_vinculo = $this->Parceiro->id;
        $this->titulo = $this->Parceiro->titulo;
        $this->codigo = $this->gerarCodigoUnico();
        $this->data_vencimento = new Data($this->pegarVencimentoVoucher());
        $this->status = new Status(Status::CRIADO);
    }

    private function pegarTipoUsuario()
    {
        if ($this->Usuario->federacao == 'FU') {
            return new TipoUsuario(TipoUsuario::FUNCIONARIO);
        } elseif ($this->Usuario->tipo->indice() == UsuarioClienteTipoUsuario::DEPENDENTE) {
            return new TipoUsuario(TipoUsuario::DEPENDENTE);
        }
        return new TipoUsuario(TipoUsuario::TITULAR);
    }

    private function pegarVencimentoVoucher()
    {
        if ($this->Parceiro->prazo_voucher_fixo->valido()) {
            return $this->Parceiro->prazo_voucher_fixo->date();
        }
        return dataAdicionar(hoje(), $this->Parceiro->prazo_voucher, 'dias');
    }

    private function verificarSeJaExisteVoucherComPrazo(): bool
    {
        if (!$this->validarSeParceiroTemLimiteMaximo()) {
            return false;
        }
        $voucher = $this
            ->campo(['id', 'data_vencimento', 'status'])
            ->where([
                ['data_criacao', 'between', [dataPrimeiroDiaMes(hoje()), dataUltimoDiaMes(hoje(), 'Y-m-d H:i:s')]],
                ['usuario', $this->Usuario->get('id')],
                ['empresa', $this->Usuario->id_admin_empresa],
                ['vinculo', $this->Parceiro->id],
                ['status', 'in', [1, 2]]
            ])
            ->order('status', 'DESC')
            ->primeiro();

        if (empty($voucher) || ($voucher->status == 1 && $voucher->data_vencimento < hoje())) {
            return false;
        } elseif ($voucher->status == 2) {
            mensagemErro('Sem saldo!', 'Você já utilizou o voucher mensal desta parceria.');
        }

        $this->recriarEntity($voucher->id);
        return true;
    }

    private function verificarSeTemVoucherCriadoAgora(): bool
    {
        $voucher = $this
            ->campo(['id'])
            ->where([
                ['data_criacao', '>', dataRemover(agora(), 2, 'minuto', 'Y-m-d H:i:s')],
                ['usuario', $this->Usuario->get('id')],
                ['empresa', $this->Usuario->id_admin_empresa],
                ['vinculo', $this->Parceiro->id],
                ['status', 1]
            ])
            ->primeiro();
        if (empty($voucher)) {
            return false;
        }

        $this->recriarEntity($voucher->id);
        return true;
    }

    private function verificarLimiteVoucher()
    {
        if (!$this->validarSeParceiroTemLimiteMaximo()) {
            return;
        }

        $quantidade = $this->contar([
            ['data_criacao', 'between', [dataPrimeiroDiaMes(hoje()), dataUltimoDiaMes(hoje(), 'Y-m-d H:i:s')]],
            ['status', 'in', [1, 2]],
            ['empresa', $this->Usuario->id_admin_empresa],
            ['vinculo', $this->Parceiro->id]
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

    private function gerarCodigoUnico()
    {
        $codigo = strCodigo();
        if ($this->existe(['codigo', $codigo])) {
            return $this->gerarCodigoUnico();
        }
        return $codigo;
    }
}
