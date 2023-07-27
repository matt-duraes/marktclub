<?php

namespace App\Models\Api\ComercialFatura;

use ORM\ORM;
use Modules\Dinheiro;
use App\Classes\ComercialFatura\Status;
use System\Interface\ApiRetornoInterface;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;
use App\Classes\ComercialEmpresa\Status as ComercialEmpresaStatus;

final class UltimaFaturaModel extends ORM implements ApiRetornoInterface
{
    protected string $ormTabela = TABELA_COMERCIAL_FATURA;
    private Dinheiro $valor;

    public function __construct(
        private EmpresaEntity $Empresa
    ) {
        parent::__construct();
        if ($Empresa->status->indice() != ComercialEmpresaStatus::ATIVO) {
            $this->valor = new Dinheiro(null);
            return;
        }

        $idEmpresa = $Empresa->get('id');
        $valor = $this->pegarUltimaFatura($idEmpresa);
        if (!empty($valor)) {
            $this->montarValor($valor);
            return;
        }
        new CriarFaturaModel(Empresa: $Empresa);
        $this->montarValor($this->pegarUltimaFatura($idEmpresa));
    }

    private function pegarUltimaFatura(int $id)
    {
        return $this
            ->campo(['valor_real', 'valor_pago', 'status'])
            ->where(['id_admin_empresa', $id])
            ->order('id', 'DESC')
            ->primeiro();
    }

    private function montarValor($dado)
    {
        if (empty($dado)) {
            $this->valor = new Dinheiro(null);
            return;
        }
        $valor = $dado->status == Status::PAGA ? $dado->valor_pago : $dado->valor_real;
        $this->valor = new Dinheiro($valor);
    }

    public function retorno(): mixed
    {
        return $this->valor->decimal();
    }
}
