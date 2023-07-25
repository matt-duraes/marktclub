<?php

namespace App\Models\Api\ComercialFatura;

use ORM\ORM;
use Modules\Dinheiro;
use App\Classes\ComercialFatura\Status;
use App\Classes\ComercialEmpresa\TipoPagamento;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;

final class CriarFaturaModel extends ORM
{
    protected string $ormTabela = TABELA_COMERCIAL_FATURA;

    public function __construct(
        private EmpresaEntity $Empresa
    ) {
        parent::__construct();
        $dia = $Empresa->contrato_dia_pagamento->numero();
        $valor = $this->pegarValorReal();

        if (empty($valor)) {
            return;
        }

        $this->dado([
            'id_admin_empresa' => $Empresa->get('id'),
            'valor_real'       => $valor,
            'data_vencimento'  => date('Y-m-') . str_pad($dia, 2, '0', STR_PAD_LEFT),
            'status'           => (new Status(Status::ABERTA))->numero()
        ])->insert();
    }

    private function pegarValorReal()
    {
        $Empresa = $this->Empresa;
        if ($Empresa->tipo_pagamento->indice() == TipoPagamento::FIXO) {
            return $Empresa->contrato_valor->decimal();
        }
        if (empty($Empresa->contrato_valor->decimal())) {
            return 0;
        }
        $Usuario = new ContarUsuarioModel(
            empresa: $Empresa->get('id'),
            aposentado: $Empresa->cobrar_aposentado
        );
        $valor = $Usuario->quantidade * $Empresa->contrato_valor->decimal();
        $valorMinimo = $Empresa->contrato_valor_minimo->decimal();

        return (new Dinheiro($valor >= $valorMinimo ? $valor : $valorMinimo))->decimal();
    }
}
