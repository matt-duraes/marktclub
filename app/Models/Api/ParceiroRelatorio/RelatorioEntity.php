<?php

namespace App\Models\Api\ParceiroRelatorio;

use App\Models\Api\ComercialEmpresa\EmpresaEntity;
use App\Models\Api\ParceiroLoja\LojaEntity;
use Erro\Excecao;
use Modules\Data;
use Modules\Dinheiro;
use ORM\Entity;

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
        public int|string|null $numero_transacao = null,
        public ?Dinheiro $valor_venda = null,
        public ?Data $data_relatorio = null
    ) {
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    protected function regraSalvar(): void
    {
        $this->id_admin_empresa = $this->Empresa->get('id');
        $this->id_parceiro_loja = $this->Parceiro->get('id');
    }

    /**
     */
    protected function regraPosBuscar(): void
    {
        $this->Empresa = new EmpresaEntity();
        $this->Empresa->id($this->id_admin_empresa);
        $this->Parceiro = new LojaEntity();
        $this->Parceiro->id($this->id_parceiro_loja);
    }
}
