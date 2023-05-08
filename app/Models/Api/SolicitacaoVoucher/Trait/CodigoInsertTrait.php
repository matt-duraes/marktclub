<?php

namespace App\Models\Api\SolicitacaoVoucher\Trait;

use Modules\DataHora;
use App\Classes\SolicitacaoCodigo\Status;

trait CodigoInsertTrait
{
    protected function regraInsert()
    {
        if ($this->verificarSeJaExisteVoucher()) {
            return;
        }
        $this->verificarSeExisteVoucherLivre();
        $this->setarValoresParaInsert();
    }
    private function setarValoresParaInsert()
    {
        $this->id_admin_empresa = $this->Usuario->id_admin_empresa;
        $this->id_usuario_cliente = $this->Usuario->get('id');
        $this->id_parceiro_loja = $this->Parceiro->get('id');
        $this->data_emissao = new DataHora(agora());
        $this->status = new Status(Status::SOLICITADO);
    }

    private function verificarSeJaExisteVoucher(): bool
    {
        $voucher = $this
            ->campo(['id', 'status'])
            ->where([
                ['data_vencimento', '>=', hoje()],
                ['id_usuario_cliente', $this->Usuario->get('id')],
                ['id_admin_empresa', $this->Usuario->id_admin_empresa],
                ['id_parceiro_loja', $this->Parceiro->get('id')],
                ['status', 2]
            ])->primeiro();

        if (empty($voucher)) {
            return false;
        }

        $this->recriarEntity($voucher->id);
        return true;
    }

    private function verificarSeExisteVoucherLivre()
    {
        $id = $this
            ->campo(['id', 'codigo'])
            ->where([
                ['status', 1],
                ['id_parceiro_loja', $this->Parceiro->get('id')],
                ['data_vencimento', '>=', hoje()]
            ])
            ->primeiro(campo: 'id');

        if (empty($id)) {
            mensagemErro('Sem voucher!', 'Os vouchers esgotaram no momento, estamos providenciando mais vouchers.');
        }

        $this->id($id);
    }
}
