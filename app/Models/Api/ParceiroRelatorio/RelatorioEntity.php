<?php

namespace App\Models\Api\ParceiroRelatorio;

use ORM\Entity;
use Modules\Data;
use Modules\Dinheiro;
use App\Models\Api\AdminEmpresa\EmpresaEntity;
use App\Models\Api\ConvenioParceiro\ParceiroEntity;

final class RelatorioEntity extends Entity
{
    protected string $_tabela = TABELA_ANALYTICS_LOJA_VENDA;
    protected array $_salvar = [
        'id_admin_empresa', 'id_parceiro_loja', 'numero_transacao', 'valor_venda', 'data_relatorio'
    ];
    protected array $_buscar = [
        'id_admin_empresa', 'id_parceiro_loja', 'numero_transacao', 'valor_venda', 'data_relatorio'
    ];

    protected string $_validarSalvar = '
        numero_transacao|Número de transação|obrigatorio|vazio|inteiro
        valor_venda|Valor de venda|obrigatorio|vazio|valido
        data_relatorio|Data do relatório|obrigatorio|vazio|valido
    ';

    protected int $id_admin_empresa;
    protected int $id_parceiro_loja;

    public function __construct(
        public ?EmpresaEntity $Empresa = null,
        public ?ParceiroEntity $Parceiro = null,
        public ?int $numero_transacao = null,
        public ?Dinheiro $valor_venda = null,
        public ?Data $data_relatorio = null
    ) {
        parent::__construct();
    }

    protected function regraInsert()
    {
        $this->id_admin_empresa = $this->Empresa->get('id');
        $this->id_parceiro_loja = $this->Parceiro->get('id');
    }

    protected function regraPosBuscar()
    {
        $this->Empresa = new EmpresaEntity();
        $this->Empresa->_id($this->id_admin_empresa);
        $this->Parceiro = new ParceiroEntity();
        $this->Parceiro->_id($this->id_parceiro_loja);
    }
}
