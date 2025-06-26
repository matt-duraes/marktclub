<?php

namespace App\Models\Api\Saude\Convenio;

use App\Models\Api\Auth\Token\TokenHelper;

final class MenuModel extends AbstractOrm
{
    public string $existe = 'nao';
    public function __construct()
    {
        parent::__construct();
        $this->verificarSeExiste();
    }

    private function verificarSeExiste()
    {
        if(!$this->existe($this->pegarWhere())) {
            return;
        }
        $this->existe = 'sim';
    }

    private function pegarWhere(): array
    {
        $Token = new TokenHelper();
        return [
            ['id_admin_empresa', 'json', $Token->pegarEmpresa(erro: true)],
            ['status', 1]
        ];
    }
}
