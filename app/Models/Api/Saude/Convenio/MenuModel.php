<?php

namespace App\Models\Api\Saude\Convenio;

use App\Models\Api\Auth\Token\TokenHelper;

final class MenuModel extends AbstractOrm
{
    public string $existe = 'nao';
    public function __construct(
        int $idEmpresa
    )
    {
        parent::__construct();
        $this->verificarSeExiste($idEmpresa);
    }

    private function verificarSeExiste($idEmpresa)
    {
        if(!$this->existe([
            ['id_admin_empresa', 'json', $idEmpresa],
            ['status', 1]
        ])) {
            return;
        }
        $this->existe = 'sim';
    }
}
