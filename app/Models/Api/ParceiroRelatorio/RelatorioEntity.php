<?php

namespace App\Models\Api\ParceiroRelatorio;

use ORM\Entity;
use Modules\Data;
use Modules\Dinheiro;
use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;

final class RelatorioEntity extends Entity
{
    protected string $ormTabela = TABELA_ANALYTICS_LOJA_VENDA;
    protected array $ormSalvar = [
        'id_admin_empresa', 'id_parceiro_loja', 'numero_transacao', 'valor_venda', 'data_relatorio'
    ];
    protected array $ormBuscar = [
        'id_admin_empresa', 'id_parceiro_loja', 'numero_transacao', 'valor_venda', 'data_relatorio'
    ];
    protected string $ormValidarSalvar = '
        numero_transacao|Número de transação|obrigatorio|vazio|inteiro
        valor_venda|Valor de venda|obrigatorio|vazio|valido
        data_relatorio|Data do relatório|obrigatorio|vazio|valido
    ';
    protected int $id_admin_empresa;
    protected int $id_parceiro_loja;

    public function __construct(
        public ?EmpresaEntity $Empresa = null,
        public ?LojaEntity $Parceiro = null,
        public ?int $numero_transacao = null,
        public ?Dinheiro $valor_venda = null,
        public ?Data $data_relatorio = null
    ) {
        parent::__construct();
    }

    protected function regraSalvar()
    {
        $this->id_admin_empresa = $this->Empresa->get('id');
        $this->id_parceiro_loja = $this->Parceiro->get('id');
    }

    protected function regraPosBuscar()
    {
        $this->Empresa = new EmpresaEntity();
        $this->Empresa->id($this->id_admin_empresa);
        $this->Parceiro = new LojaEntity();
        $this->Parceiro->id($this->id_parceiro_loja);
    }
}
