<?php

namespace App\Models\Api\PagamentoEntity;

use ORM\Entity;
use Modules\Dinheiro;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;

final class PagamentoEntity extends Entity
{
    protected string $ormTabela = TABELA_COMERCIAL_PAGAMENTO;
    protected array $ormInsert = ['id_usuario_equipe', 'id_admin_empresa', 'valor'];
    protected int $id_admin_empresa;
    protected int $id_usuario_equipe;

    public function __construct(
        EmpresaEntity $Empresa,
        protected Dinheiro $valor
    ) {
        $this->id_admin_empresa = $Empresa->get('id');
        $this->id_usuario_equipe = TOKEN['usuario']->get('id');
        $this->salvar();
    }
}
