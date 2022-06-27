<?php

namespace App\Models\Api\ParceiroAcessado;

use App\Models\Api\GeralEntity;

final class AcessoEntity extends GeralEntity
{
    protected string $_tabela = TABELA_PARCEIRO_ACESSADO;
    protected array $_buscar = ['id_parceiro'];
    protected array $_insert = ['id_admin_empresa', 'id_parceiro', 'status'];

    public function __construct(
        protected ?array $id_parceiro = null
    ) {
        parent::__construct();
    }

    protected function regraInsert()
    {
        $this->id_admin_empresa = $this->idEmpresa;
        $this->status = 1;
    }

    protected function regraPosInsert()
    {
        $this->darBaixaNosAntigos();
    }

    private function darBaixaNosAntigos()
    {
        try {
            $id = $this->prop('id');
            $this->dado([
                'status' => 2
            ])->where([
                ['id_admin_empresa', $this->id_admin_empresa],
                ['status', 1],
                ['id', '!=', $id]
            ])->update();
        } catch (\Throwable) {
        }
    }
}
